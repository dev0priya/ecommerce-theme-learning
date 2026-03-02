<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function redirect($url) {
    $basePath = '/ecommerce-theme-learning/';
    header('Location: ' . $basePath . ltrim($url, '/'));
    exit();
}


function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
// Function to fetch a specific setting
function getSetting($key, $pdo) {
    $stmt = $pdo->prepare(
        "SELECT setting_value FROM settings WHERE setting_key = ?"
    );
    $stmt->execute([$key]);
    $result = $stmt->fetch();

    return $result ? $result['setting_value'] : '';
}
/**
 * Universal Image Upload Function
 *
 * @param array  $file   The $_FILES['input_name'] array
 * @param string $folder The subfolder inside 'assets/' (default: 'uploads')
 * @return string|false  Returns new filename on success, false on failure
 */
function uploadImage($file, $folder = 'uploads') {

    // 1. Basic error check
    if (!isset($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    // 2. Target directory (absolute path)
    $targetDir = __DIR__ . "/../assets/" . $folder . "/";

    // Create directory if not exists
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // 3. Security check — is it really an image?
    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        return false;
    }

    // 4. Extension validation
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed   = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($extension, $allowed)) {
        return false;
    }

    // 5. Unique filename
    $newFileName = time() . "_" . rand(1000, 9999) . "." . $extension;
    $targetFile  = $targetDir . $newFileName;

    // 6. Move file
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return $newFileName;
    }

    return false;
}

function sendEmail($to, $subject, $body) {

    $mail = new PHPMailer(true);

    try {
        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        // 🔴 PUT YOUR EMAIL & APP PASSWORD HERE
        $mail->Username   = 'your.email@gmail.com';
        $mail->Password   = 'xxxx xxxx xxxx xxxx';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender & recipient
        $mail->setFrom('your.email@gmail.com', 'My Shop');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}

