<?php
// include/auth.php

/**
 * Check if user is logged in
 * If not logged in, redirect to sign-in page
 */
function checkLogin() {
    // If there is no user_id in session, user is not authenticated
    if (!isset($_SESSION['user_id'])) {
        redirect('sign-in.php');
    }
}

/**
 * Handle user login
 * 
 * @param string $email
 * @param string $password
 * @param PDO $pdo
 * @return bool
 */
function login($email, $password, $pdo) {

    // 1. Prepare SQL to find user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    // 2. Fetch user record
    $user = $stmt->fetch();

    // 3. Verify password (hashed password check)
    if ($user && password_verify($password, $user['password'])) {

        // 4. Regenerate session ID to prevent session fixation attack
        session_regenerate_id(true);

        // 5. Store user data in session
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        return true;
    }

    // Login failed
    return false;
}
