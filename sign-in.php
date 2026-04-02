<?php
require 'include/load.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = "All fields are required.";
    } else {
        // Fetch user by email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Store session
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: dashboard.php");
            } else {
                header("Location: modules/user/dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Access | Project A Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            transition: background 0.3s ease;
        }

        /* --- BIGGER & SLEEKER CARD --- */
        .auth-card {
            background: #1e293b; /* Deep Navy/Black Background */
            border: 1px solid #334155; /* Subtle Border */
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 1280px; /* Big Size */
            min-height: 700px;
        }

        /* --- INPUTS: PROFESSIONAL DARK STYLE --- */
        .input-group {
            position: relative;
            margin-bottom: 24px;
        }

        .auth-input {
            width: 100%;
            background: #0f172a; /* Darker than card */
            border: 2px solid #334155;
            padding: 16px 18px 16px 52px;
            border-radius: 16px;
            color: white;
            font-weight: 600;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        .auth-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 5px rgba(99, 102, 241, 0.1);
        }

        /* Input Icons using Remix Icons */
        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 22px;
            color: #64748b;
        }

        /* --- BUTTONS: CORPORTATE GRADIENT & GLOW --- */
        .btn-login {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 16px;
            border-radius: 16px;
            font-weight: 900;
            width: 100%;
            transition: 0.3s;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 20px -3px rgba(79, 102, 241, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(79, 102, 241, 0.4);
        }

        /* --- PREMIUM BANNER: ADAPTIVE BACKGROUND --- */
        .banner-side {
            background: linear-gradient(rgba(15, 23, 42, 0.4), rgba(15, 23, 42, 0.9)), 
                        url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1964&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6 lg:p-12">

    <div class="auth-card flex shadow-xl">
        
        <div class="hidden lg:flex lg:w-1/2 banner-side p-16 flex-col justify-between text-white relative">
            <div>
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center font-black text-2xl text-white">A</div>
                    <span class="text-3xl font-black tracking-tighter uppercase text-white">Project A</span>
                </div>
                <h2 class="text-4xl font-black leading-tight text-white mb-6">Revolutionize <br>your <span class="text-indigo-400">E-Commerce</span> management.</h2>
                <p class="text-sm font-medium opacity-80 max-w-sm">Secure and efficient access to your real-time analytics and management tools.</p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md p-8 rounded-3xl border border-white/10 mt-auto">
                <p class="text-sm font-medium opacity-80 italic">"Technology is best when it brings people together."</p>
                <div class="mt-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-500"></div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest opacity-60 block">Management System</span>
                        <span class="text-sm font-bold opacity-90">System Operations</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 p-10 lg:p-16">
            <div class="mb-12">
                <h2 class="text-4xl font-black text-white tracking-tighter">Account Access</h2>
                <p class="text-slate-400 font-medium mt-1">Please enter your authorized credentials.</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-8 p-5 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-center gap-3 text-rose-500">
                    <iconify-icon icon="solar:danger-circle-bold" class="text-xl"></iconify-icon>
                    <span class="text-sm font-bold"><?= htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[2px] mb-2">Corporate Email</label>
                    <iconify-icon icon="solar:letter-bold-duotone" class="input-icon"></iconify-icon>
                    <input type="email" name="email" class="auth-input" placeholder="admin@project-a.co" required>
                </div>

                <div class="input-group">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[2px]">Password</label>
                        <a href="forgot-password.php" class="text-[10px] font-black text-indigo-400 uppercase tracking-widest hover:text-indigo-300">Forgot Credentials?</a>
                    </div>
                    <iconify-icon icon="solar:lock-password-bold-duotone" class="input-icon"></iconify-icon>
                    <input type="password" name="password" class="auth-input" placeholder="••••••••" required>
                </div>

                <div class="flex items-center gap-2 mb-10">
                    <input type="checkbox" id="remember" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500">
                    <label for="remember" class="text-xs font-bold text-slate-400">Remember this device</label>
                </div>

                <button type="submit" class="btn-login flex items-center justify-center gap-2">
                    <span>Finalize Access</span>
                    <iconify-icon icon="solar:check-read-bold" class="text-xl"></iconify-icon>
                </button>
            </form>

            <div class="mt-12 text-center">
                <p class="text-sm text-slate-500 font-medium">
                    New to the network? <a href="sign-up.php" class="text-indigo-400 font-bold hover:underline">Register Account</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>