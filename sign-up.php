<?php
require 'include/load.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $gender   = $_POST['gender'] ?? null;

    if ($name === '' || $email === '' || $password === '') {
        $error = "All fields are required.";
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } 
    elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } 
    else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "This email is already registered.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, gender, role) VALUES (?, ?, ?, ?, 'user')");
            $stmt->execute([$name, $email, $hashedPassword, $gender]);
            $success = "Account created successfully! You can now login.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Project A</title>
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
            min-height: 750px;
        }

        /* --- BANNER SIDE WITH TEXT PROTECTION --- */
        .banner-side {
            background: linear-gradient(rgba(15, 23, 42, 0.4), rgba(15, 23, 42, 0.9)), 
                        url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=2015&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* Text Pop Colors */
        .text-pop-amber { color: #fbbf24; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5)); }
        .text-pop-indigo { color: #818cf8; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5)); }

        .glass-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 30px;
            border-radius: 24px;
        }

        /* Form Inputs */
        .input-group { position: relative; margin-bottom: 20px; }
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
        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 22px;
            color: #64748b;
        }

        .btn-register {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 16px;
            border-radius: 16px;
            font-weight: 900;
            width: 100%;
            transition: 0.3s;
            box-shadow: 0 10px 20px -3px rgba(79, 102, 241, 0.3);
        }

        .gender-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 10px 20px;
            background: #0f172a;
            border: 2px solid #334155;
            border-radius: 12px;
            transition: 0.3s;
            color: #94a3b8;
        }
        input[type="radio"]:checked + .gender-option {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.15);
            color: white;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">

    <div class="auth-card flex shadow-2xl">
        
        <div class="hidden lg:flex lg:w-1/2 banner-side p-16 flex-col justify-between text-white">
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-12">
                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center font-black text-2xl shadow-lg shadow-indigo-500/50 text-white">A</div>
                    <span class="text-3xl font-black tracking-tighter uppercase text-white">Project A</span>
                </div>
                
                <h2 class="text-5xl font-black leading-tight mb-6 drop-shadow-2xl">
                    Join the next <br><span class="text-pop-amber">Generation</span> <br>of <span class="text-pop-indigo">Commerce.</span>
                </h2>
                <p class="text-base font-bold text-slate-200 opacity-95 max-w-sm">Create an account to start managing your merchant dashboard instantly.</p>
            </div>
            
            <div class="glass-box mt-auto relative z-10">
                <p class="text-sm font-bold text-white italic leading-relaxed">"The best way to predict the future is to create it. Scale your business today."</p>
                <div class="mt-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-400 flex items-center justify-center font-black text-slate-900 shadow-md">PA</div>
                    <div>
                        <span class="text-[10px] font-black text-pop-amber uppercase tracking-[2px] block">Verified Platform</span>
                        <span class="text-sm font-black text-white">Merchant Network</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 p-10 lg:p-16">
            <div class="mb-10">
                <h2 class="text-4xl font-black text-white tracking-tighter">Get Started</h2>
                <p class="text-slate-400 font-medium mt-1">Create your personal merchant account.</p>
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
                <div class="input-group">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[2px] mb-2">Full Name</label>
                    <iconify-icon icon="solar:user-bold-duotone" class="input-icon"></iconify-icon>
                    <input type="text" name="name" class="auth-input" placeholder="John Doe" required>
                </div>

                <div class="input-group">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[2px] mb-2">Email Address</label>
                    <iconify-icon icon="solar:letter-bold-duotone" class="input-icon"></iconify-icon>
                    <input type="email" name="email" class="auth-input" placeholder="john@example.com" required>
                </div>

                <div class="input-group">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[2px] mb-2">Secure Password</label>
                    <iconify-icon icon="solar:lock-password-bold-duotone" class="input-icon"></iconify-icon>
                    <input type="password" name="password" class="auth-input" placeholder="Min. 6 characters" required>
                </div>

                <div class="mb-8">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[2px] mb-3">Gender Selection</label>
                    <div class="flex gap-4">
                        <label class="flex-1">
                            <input type="radio" name="gender" value="Male" class="hidden" checked>
                            <div class="gender-option">
                                <iconify-icon icon="solar:user-rounded-bold-duotone"></iconify-icon>
                                <span class="text-sm font-bold">Male</span>
                            </div>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="gender" value="Female" class="hidden">
                            <div class="gender-option">
                                <iconify-icon icon="solar:user-rounded-bold-duotone"></iconify-icon>
                                <span class="text-sm font-bold">Female</span>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-register flex items-center justify-center gap-2">
                    <span>Create Free Account</span>
                    <iconify-icon icon="solar:add-circle-bold" class="text-xl"></iconify-icon>
                </button>
            </form>

            <div class="mt-10 text-center">
                <p class="text-sm text-slate-500 font-medium">
                    Already part of the network? <a href="sign-in.php" class="text-indigo-400 font-bold hover:underline">Login Now</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>