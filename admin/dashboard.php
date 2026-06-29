<?php
require_once 'config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$message = '';
$error = '';

// Save settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_settings'])) {
        $site_name = trim($_POST['site_name']);
        $primary_color = trim($_POST['primary_color']);
        $secondary_color = trim($_POST['secondary_color']);
        
        // Update config
        $admin_config['site_name'] = $site_name;
        $admin_config['primary_color'] = $primary_color;
        $admin_config['secondary_color'] = $secondary_color;
        
        // Update all HTML files
        $updated = updateAllFiles($site_name, $primary_color, $secondary_color);
        
        if (saveAdminConfig($admin_config)) {
            $message = '✅ Site settings saved! ' . count($updated) . ' files updated.';
        } else {
            $error = '❌ Could not save settings.';
        }
    }
    
    if (isset($_POST['save_codes'])) {
        $admin_config['header_code'] = $_POST['header_code'];
        $admin_config['footer_code'] = $_POST['footer_code'];
        
        if (saveAdminConfig($admin_config)) {
            $message = '✅ Header/Footer codes saved!';
        } else {
            $error = '❌ Could not save codes.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Admin - SEO Toolkit Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            color: #1a2634;
        }
        .container { max-width: 900px; margin: 0 auto; padding: 40px 20px; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }
        .header h1 { font-size: 28px; font-weight: 700; }
        .logout-btn {
            padding: 10px 20px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 999px;
            color: #dc2626;
            text-decoration: none;
            font-weight: 600;
        }
        .card {
            background: white;
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
        }
        .card h2 {
            font-size: 18px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        .form-group { margin-bottom: 20px; }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 13px;
            color: #374151;
        }
        input[type="text"], input[type="color"], textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
        }
        textarea {
            min-height: 150px;
            resize: vertical;
            font-family: monospace;
            font-size: 13px;
        }
        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        button {
            padding: 12px 24px;
            background: #0057ff;
            color: white;
            border: none;
            border-radius: 999px;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover { background: #0047d1; }
        .message {
            background: #d1fae5;
            color: #10b981;
            padding: 14px 20px;
            border-radius: 16px;
            margin-bottom: 24px;
        }
        .error {
            background: #fee2e2;
            color: #dc2626;
            padding: 14px 20px;
            border-radius: 16px;
            margin-bottom: 24px;
        }
        .info-note {
            background: #eef2ff;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            color: #0057ff;
            margin-top: 12px;
        }
        @media (max-width: 640px) {
            .row-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚙️ Simple Admin Panel</h1>
            <a href="logout.php" class="logout-btn">🚪 Logout</a>
        </div>
        
        <?php if ($message): ?>
            <div class="message">✅ <?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error">❌ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <!-- Site Settings Card -->
        <div class="card">
            <h2>🎨 Site Name & Colors</h2>
            <p class="card-subtitle">Changes will apply to ALL pages and tools instantly.</p>
            
            <form method="POST">
                <div class="row-2">
                    <div class="form-group">
                        <label>🏷️ Site Name</label>
                        <input type="text" name="site_name" value="<?php echo htmlspecialchars($admin_config['site_name']); ?>">
                    </div>
                    <div class="form-group">
                        <label>🎨 Primary Color</label>
                        <input type="color" name="primary_color" value="<?php echo $admin_config['primary_color']; ?>">
                    </div>
                    <div class="form-group">
                        <label>🎨 Secondary Color</label>
                        <input type="color" name="secondary_color" value="<?php echo $admin_config['secondary_color']; ?>">
                    </div>
                </div>
                
                <div class="info-note">
                    💡 This updates the brand name and colors on: Homepage, About, Contact, Privacy, Terms, Disclaimer, and all 6 SEO tools.
                </div>
                
                <button type="submit" name="save_settings" style="margin-top: 20px;">💾 Save Site Settings</button>
            </form>
        </div>
        
        <!-- Header/Footer Codes Card -->
        <div class="card">
            <h2>💻 Header & Footer Codes</h2>
            <p class="card-subtitle">Add Google Analytics, AdSense, Facebook Pixel, or any custom scripts.</p>
            
            <form method="POST">
                <div class="form-group">
                    <label>📊 Header Code (injects before &lt;/head&gt;)</label>
                    <textarea name="header_code" placeholder="Google Analytics, meta tags, custom CSS..."><?php echo htmlspecialchars($admin_config['header_code']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>📊 Footer Code (injects before &lt;/body&gt;)</label>
                    <textarea name="footer_code" placeholder="Chat widgets, tracking pixels, custom JS..."><?php echo htmlspecialchars($admin_config['footer_code']); ?></textarea>
                </div>
                
                <div class="info-note">
                    💡 These codes will be added to EVERY page on your website (all tools, all static pages).
                </div>
                
                <button type="submit" name="save_codes" style="margin-top: 20px;">💾 Save Header/Footer Codes</button>
            </form>
        </div>
        
        <!-- Blog Manager Card -->
        <div class="card">
            <h2>📝 Blog Manager</h2>
            <p class="card-subtitle">Create, edit, and manage blog posts displayed on the home page and blog page.</p>
            
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <a href="blog.php?action=create" class="btn" style="text-decoration:none;">➕ New Post</a>
                <a href="blog.php" class="btn" style="text-decoration:none; background:#f0f2f5; color:#374151;">📋 Manage Posts</a>
            </div>
        </div>
        
        <!-- Quick Guide -->
        <div class="card">
            <h2>📋 What This Admin Does</h2>
            <div style="margin-top: 8px; color: #6b7280; font-size: 14px; line-height: 1.7;">
                <p>✅ <strong>Site Name</strong> - Changes the brand name everywhere (navigation bar, footer, etc.)</p>
                <p>✅ <strong>Colors</strong> - Changes primary and secondary colors across ALL pages and tools</p>
                <p>✅ <strong>Header Code</strong> - Add Google Analytics, AdSense verification, custom CSS</p>
                <p>✅ <strong>Footer Code</strong> - Add chat widgets, remarketing pixels, custom JavaScript</p>
                <p>❌ <strong>Does NOT change</strong> - Page content, tool functionality, blog posts, or HTML structure</p>
            </div>
        </div>
    </div>
</body>
</html>