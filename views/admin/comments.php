<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments | PFSRV SEO Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="admin-body">
    <?php require __DIR__ . '/_nav.php'; ?>
    <div style="max-width:900px;margin:0 auto;padding:32px 24px;">
        <h1 style="font-size:1.75rem;margin:0 0 24px;">Pending Comments (<?php echo $total; ?>)</h1>

        <?php if (empty($comments)): ?>
        <div class="admin-card" style="text-align:center;padding:48px;">
            <p style="color:var(--text-muted);margin:0;">No pending comments. All caught up! 🎉</p>
        </div>
        <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:16px;">
            <?php foreach ($comments as $c): ?>
            <div class="admin-card">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div>
                        <div style="font-weight:600;font-size:14px;"><?php echo \App\Helpers\SEO::esc($c['name']); ?></div>
                        <div style="font-size:13px;color:var(--text-muted);"><?php echo \App\Helpers\SEO::esc($c['email']); ?> · <?php echo date('M j, Y g:i a', strtotime($c['created_at'])); ?></div>
                        <div style="font-size:13px;color:var(--text-muted);">On: <a href="/blog/<?php echo \App\Helpers\SEO::esc($c['post_slug'] ?? ''); ?>" style="color:var(--primary);"><?php echo \App\Helpers\SEO::esc($c['post_title']); ?></a></div>
                    </div>
                    <div style="display:flex;gap:4px;">
                        <form method="POST" action="/admin/comments/approve/<?php echo $c['id']; ?>" style="display:inline;">
                            <button type="submit" class="btn btn-sm" style="background:var(--success);color:#fff;border:none;cursor:pointer;border-radius:8px;padding:6px 14px;font-weight:600;font-size:13px;">Approve</button>
                        </form>
                        <form method="POST" action="/admin/comments/delete/<?php echo $c['id']; ?>" style="display:inline;" onsubmit="return confirm('Delete this comment?');">
                            <button type="submit" class="btn btn-sm" style="background:var(--error);color:#fff;border:none;cursor:pointer;border-radius:8px;padding:6px 14px;font-weight:600;font-size:13px;">Delete</button>
                        </form>
                    </div>
                </div>
                <div style="padding:12px;background:var(--bg-secondary);border-radius:var(--radius);font-size:14px;line-height:1.6;color:var(--text-secondary);">
                    <?php echo $c['content']; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
