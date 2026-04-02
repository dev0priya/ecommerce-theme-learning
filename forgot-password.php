<?php
require 'include/load.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validation
    if ($email === '' || $new_password === '' || $confirm_password === '') {
        $error = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Update password logic
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            if ($update->execute([$hashedPassword, $email])) {
                $success = "Password updated successfully! You can now login.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        } else {
            $error = "No account found with this email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Project A</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
        }

        .auth-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.7);
            width: 100%;
            max-width: 1200px;
            min-height: 700px;
        }

        .banner-side {
            background: linear-gradient(rgba(15, 23, 42, 0.5), rgba(15, 23, 42, 0.9)), 
                        url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        .text-pop-amber { color: #fbbf24; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5)); }
        
        .auth-input {
            width: 100%;
            background: #0f172a;
            border: 2px solid #334155;
            padding: 14px 16px 14px 50px;
            border-radius: 16px;
            color: white;
            font-weight: 600;
            outline: none;
            transition: 0.3s;
        }

        .auth-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 5px rgba(99, 102, 241, 0.1);
        }

        .btn-reset {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 16px;
            border-radius: 16px;
            font-weight: 900;
            width: 100%;
            transition: 0.3s;
            box-shadow: 0 10px 20px -3px rgba(79, 102, 241, 0.3);
        }

        .btn-reset:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(79, 102, 241, 0.4);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">

    <div class="auth-card flex shadow-2xl">
        
        <div class="hidden lg:flex lg:w-1/2 banner-side p-16 flex-col justify-between text-white">
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-12">
                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center font-black text-2xl text-white shadow-lg">A</div>
                    <span class="text-3xl font-black tracking-tighter uppercase">Project A</span>
                </div>
                
                <h2 class="text-5xl font-black leading-tight mb-6">
                    Recover your <br><span class="text-pop-amber">Identity</span> <br>on the Network.
                </h2>
                <p class="text-base font-bold text-slate-200 opacity-95 max-w-sm">Reset your credentials to regain access to your merchant dashboard and tools.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 p-10 lg:p-16">
            <div class="mb-10">
                <h2 class="text-4xl font-black text-white tracking-tighter">Reset Password</h2>
                <p class="text-slate-400 font-medium mt-1">Regain access to your account securely.</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-center gap-3 text-rose-500">
                    <iconify-icon icon="solar:danger-circle-bold" class="text-xl"></iconify-icon>
                    <span class="text-sm font-bold"><?= htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center gap-3 text-emerald-500">
                    <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>
                    <span class="text-sm font-bold"><?= htmlspecialchars($success); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="relative mb-6">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[2px] mb-2">Registered Email</label>
                    <iconify-icon icon="solar:letter-bold-duotone" class="absolute left-4 top-[44px] text-2xl text-slate-500"></iconify-icon>
                    <input type="email" name="email" class="auth-input" placeholder="Enter your email" required>
                </div>

                <div class="relative mb-6">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[2px] mb-2">New Password</label>
                    <iconify-icon icon="solar:lock-password-bold-duotone" class="absolute left-4 top-[44px] text-2xl text-slate-500"></iconify-icon>
                    <input type="password" name="new_password" class="auth-input" placeholder="••••••••" required>
                </div>

                <div class="relative mb-10">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[2px] mb-2">Confirm New Password</label>
                    <iconify-icon icon="solar:shield-check-bold-duotone" class="absolute left-4 top-[44px] text-2xl text-slate-500"></iconify-icon>
                    <input type="password" name="confirm_password" class="auth-input" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-reset flex items-center justify-center gap-2">
                    <span>Rebuild Credentials</span>
                    <iconify-icon icon="solar:refresh-bold" class="text-xl"></iconify-icon>
                </button>
            </form>

            <div class="mt-10 text-center">
                <p class="text-sm text-slate-500 font-medium">
                    Remembered your password? <a href="sign-in.php" class="text-indigo-400 font-bold hover:underline">Sign In</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>