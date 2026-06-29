<?php
require_once 'config.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// First time setup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setup'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Valid email required.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be 8+ characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $admin_config['email'] = $email;
        $admin_config['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        $admin_config['setup_complete'] = true;
        
        if (saveAdminConfig($admin_config)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $email;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Cannot save configuration. Check folder permissions.';
        }
    }
}

// Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (isset($admin_config['email']) && $email === $admin_config['email'] && password_verify($password, $admin_config['password_hash'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $email;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo empty($admin_config['setup_complete']) ? 'Setup' : 'Login'; ?> | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667085 0%, #1a2634 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }
        .logo { text-align: center; margin-bottom: 32px; }
        .logo h1 { font-size: 24px; color: #1a2634; }
        .logo p { color: #667085; font-size: 14px; margin-top: 8px; }
        h2 { font-size: 22px; margin-bottom: 8px; }
        .subtitle { color: #667085; margin-bottom: 28px; font-size: 14px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #1a2634; }
        input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e4e9f0;
            border-radius: 12px;
            font-size: 14px;
        }
        input:focus {
            outline: none;
            border-color: #0057ff;
            box-shadow: 0 0 0 3px rgba(0,87,255,0.1);
        }
        button {
            width: 100%;
            padding: 12px;
            background: #0057ff;
            color: white;
            border: none;
            border-radius: 999px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover { background: #0047d1; }
        .error {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">
            <h1>SEO Toolkit Pro</h1>
            <p>Admin Panel</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (empty($admin_config['setup_complete'])): ?>
            <h2>First Time Setup</h2>
            <p class="subtitle">Create your admin account</p>
            <form method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password (min 8 characters)</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" name="setup">Create Account →</button>
            </form>
        <?php else: ?>
            <h2>Welcome Back</h2>
            <p class="subtitle">Sign in to manage your site</p>
            <form method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="login">Sign In →</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>