<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribers | PFSRV SEO Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="admin-body">
    <?php require __DIR__ . '/_nav.php'; ?>
    <div style="max-width:800px;margin:0 auto;padding:32px 24px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
            <h1 style="font-size:1.75rem;margin:0;">Subscribers (<?php echo count($subscribers); ?>)</h1>
        </div>

        <div class="admin-card" style="padding:0;overflow:hidden;">
            <?php if (empty($subscribers)): ?>
            <div style="text-align:center;padding:48px;">
                <p style="color:var(--text-muted);margin:0;">No subscribers yet.</p>
            </div>
            <?php else: ?>
            <div class="responsive-table">
                <table class="admin-table">
                    <thead><tr><th>Email</th><th>Name</th><th>Subscribed</th></tr></thead>
                    <tbody>
                        <?php foreach ($subscribers as $s): ?>
                        <tr>
                            <td style="font-weight:600;"><?php echo \App\Helpers\SEO::esc($s['email']); ?></td>
                            <td><?php echo \App\Helpers\SEO::esc($s['name'] ?: '—'); ?></td>
                            <td style="font-size:13px;color:var(--text-muted);"><?php echo date('M j, Y', strtotime($s['subscribed_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
