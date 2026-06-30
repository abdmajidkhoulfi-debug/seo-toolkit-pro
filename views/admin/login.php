<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $hasUsers ? 'Admin Login' : 'Setup'; ?> | PFSRV SEO</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="admin-body" style="display:flex;align-items:center;justify-content:center;min-height:100vh;">
    <div style="width:100%;max-width:420px;padding:24px;">
        <div style="text-align:center;margin-bottom:32px;">
            <a href="/" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;color:var(--text);margin-bottom:24px;">
                <span style="width:40px;height:40px;border-radius:12px;display:grid;place-items:center;color:#fff;background:linear-gradient(135deg,var(--primary),color-mix(in srgb, var(--primary) 60%, #fff));font-weight:800;">P</span>
                <span style="font-family:var(--font-display);font-size:1.2rem;font-weight:700;">PFSRV SEO</span>
            </a>
            <h1 style="font-size:1.5rem;margin:0 0 8px;"><?php echo $hasUsers ? 'Welcome Back' : 'Setup Your Admin'; ?></h1>
            <p style="color:var(--text-secondary);font-size:14px;margin:0;">
                <?php echo $hasUsers ? 'Sign in to manage your site' : 'Create your admin account to get started'; ?>
            </p>
        </div>

        <div class="card" style="padding:32px;">
            <?php if (!empty($error)): ?>
            <div style="padding:12px 16px;background:color-mix(in srgb, var(--error) 10%, transparent);border:1px solid color-mix(in srgb, var(--error) 30%, transparent);border-radius:var(--radius);color:var(--error);font-size:14px;margin-bottom:16px;"><?php echo \App\Helpers\SEO::esc($error); ?></div>
            <?php endif; ?>

            <form method="POST">
                <?php if (!$hasUsers): ?>
                <div style="margin-bottom:16px;">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-input" value="Admin" required>
                </div>
                <?php endif; ?>
                <div style="margin-bottom:16px;">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="admin@example.com" required>
                </div>
                <div style="margin-bottom:24px;">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Min 8 characters" required minlength="8">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    <?php echo $hasUsers ? 'Sign In' : 'Create Account'; ?>
                </button>
            </form>
        </div>

        <p style="text-align:center;font-size:13px;color:var(--text-muted);margin-top:24px;">
            <a href="/" style="color:var(--primary);">← Back to site</a>
        </p>
    </div>
</body>
</html>
