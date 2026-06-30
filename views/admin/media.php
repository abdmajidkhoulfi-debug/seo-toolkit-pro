<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Library | PFSRV SEO Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="admin-body">
    <?php require __DIR__ . '/_nav.php'; ?>
    <div style="max-width:1000px;margin:0 auto;padding:32px 24px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
            <h1 style="font-size:1.75rem;margin:0;">Media Library</h1>
        </div>

        <div class="admin-card" style="margin-bottom:24px;">
            <h2 style="font-size:1.1rem;margin:0 0 16px;">Upload Media</h2>
            <form method="POST" enctype="multipart/form-data">
                <div style="display:flex;gap:12px;align-items:flex-end;">
                    <div style="flex:1;">
                        <input type="file" name="file" class="form-input" accept="image/*,.pdf,.zip" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                </div>
            </form>
        </div>

        <?php if (empty($media)): ?>
        <div class="admin-card" style="text-align:center;padding:48px;">
            <p style="color:var(--text-muted);margin:0;">No media uploaded yet.</p>
        </div>
        <?php else: ?>
        <div class="media-grid">
            <?php foreach ($media as $m): ?>
            <div class="media-item">
                <?php if (str_starts_with($m['mime_type'], 'image/')): ?>
                <img src="/storage/media/<?php echo \App\Helpers\SEO::esc($m['filename']); ?>" alt="<?php echo \App\Helpers\SEO::esc($m['alt_text'] ?: $m['original_name']); ?>" loading="lazy">
                <?php else: ?>
                <div style="display:grid;place-items:center;height:100%;font-size:32px;">📄</div>
                <?php endif; ?>
                <div class="media-info">
                    <div style="font-size:12px;"><?php echo \App\Helpers\SEO::esc($m['original_name']); ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
