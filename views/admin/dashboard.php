<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | PFSRV SEO Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="admin-body">
    <?php $saved = $_GET['saved'] ?? null; ?>
    <?php if ($saved): ?>
    <div class="toast toast-success">Settings saved successfully!</div>
    <?php endif; ?>

    <?php require __DIR__ . '/_nav.php'; ?>

    <div style="max-width:1200px;margin:0 auto;padding:32px 24px;">
        <h1 style="font-size:1.75rem;margin:0 0 32px;">Dashboard</h1>

        <!-- Stats -->
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));gap:16px;margin-bottom:32px;">
            <div class="stat-card">
                <div style="display:flex;align-items:center;gap:16px;">
                    <div class="stat-icon" style="background:var(--primary-soft);color:var(--primary);">📝</div>
                    <div>
                        <div style="font-size:24px;font-weight:800;"><?php echo $stats['published']; ?></div>
                        <div style="font-size:13px;color:var(--text-muted);">Published</div>
                    </div>
                </div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:8px;"><?php echo $stats['drafts']; ?> drafts</div>
            </div>
            <div class="stat-card">
                <div style="display:flex;align-items:center;gap:16px;">
                    <div class="stat-icon" style="background:color-mix(in srgb, var(--secondary) 15%, transparent);color:var(--secondary);">💬</div>
                    <div>
                        <div style="font-size:24px;font-weight:800;"><?php echo $stats['pending_comments']; ?></div>
                        <div style="font-size:13px;color:var(--text-muted);">Pending Comments</div>
                    </div>
                </div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:8px;"><?php echo $stats['comments']; ?> total</div>
            </div>
            <div class="stat-card">
                <div style="display:flex;align-items:center;gap:16px;">
                    <div class="stat-icon" style="background:color-mix(in srgb, var(--accent) 15%, transparent);color:var(--accent);">📂</div>
                    <div>
                        <div style="font-size:24px;font-weight:800;"><?php echo $stats['categories']; ?></div>
                        <div style="font-size:13px;color:var(--text-muted);">Categories</div>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div style="display:flex;align-items:center;gap:16px;">
                    <div class="stat-icon" style="background:color-mix(in srgb, var(--success) 15%, transparent);color:var(--success);">📧</div>
                    <div>
                        <div style="font-size:24px;font-weight:800;"><?php echo $stats['subscribers']; ?></div>
                        <div style="font-size:13px;color:var(--text-muted);">Subscribers</div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
            <!-- Recent Posts -->
            <div class="admin-card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                    <h2 style="font-size:1.1rem;margin:0;">Recent Posts</h2>
                    <a href="/admin/posts" class="btn btn-sm btn-ghost">View All →</a>
                </div>
                <?php if (empty($recentPosts)): ?>
                <p style="color:var(--text-muted);font-size:14px;">No posts yet. <a href="/admin/posts/create" style="color:var(--primary);">Create your first post</a></p>
                <?php else: ?>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <?php foreach ($recentPosts as $p): ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;background:var(--bg-secondary);border-radius:var(--radius);">
                        <div>
                            <div style="font-weight:600;font-size:14px;"><?php echo \App\Helpers\SEO::esc($p['title']); ?></div>
                            <div style="font-size:12px;color:var(--text-muted);"><?php echo date('M j, Y', strtotime($p['created_at'])); ?></div>
                        </div>
                        <span class="badge" style="background:<?php echo $p['status'] === 'published' ? 'color-mix(in srgb, var(--success) 15%, transparent)' : 'color-mix(in srgb, var(--accent) 15%, transparent)'; ?>;color:<?php echo $p['status'] === 'published' ? 'var(--success)' : 'var(--accent)'; ?>;">
                            <?php echo ucfirst($p['status']); ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Site Settings -->
            <div class="admin-card">
                <h2 style="font-size:1.1rem;margin:0 0 16px;">Site Settings</h2>
                <form method="POST" action="/admin/settings">
                    <div style="margin-bottom:12px;">
                        <label class="form-label">Site Name</label>
                        <input type="text" name="site_name" class="form-input" value="<?php echo \App\Helpers\SEO::esc($settings['site_name'] ?? 'PFSRV SEO'); ?>">
                    </div>
                    <div style="margin-bottom:12px;">
                        <label class="form-label">Site Description</label>
                        <textarea name="site_description" class="form-textarea" rows="2"><?php echo \App\Helpers\SEO::esc($settings['site_description'] ?? ''); ?></textarea>
                    </div>
                    <div style="margin-bottom:12px;">
                        <label class="form-label">Header Code (Google Analytics, etc.)</label>
                        <textarea name="header_code" class="form-textarea" rows="3" style="font-family:monospace;font-size:13px;"><?php echo \App\Helpers\SEO::esc($settings['header_code'] ?? ''); ?></textarea>
                    </div>
                    <div style="margin-bottom:16px;">
                        <label class="form-label">Footer Code</label>
                        <textarea name="footer_code" class="form-textarea" rows="3" style="font-family:monospace;font-size:13px;"><?php echo \App\Helpers\SEO::esc($settings['footer_code'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
