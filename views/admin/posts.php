<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts | PFSRV SEO Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="admin-body">
    <?php require __DIR__ . '/_nav.php'; ?>
    <div style="max-width:1200px;margin:0 auto;padding:32px 24px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
            <h1 style="font-size:1.75rem;margin:0;">Posts (<?php echo $total; ?>)</h1>
            <a href="/admin/posts/create" class="btn btn-primary btn-sm">+ New Post</a>
        </div>

        <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
            <a href="/admin/posts" class="btn btn-sm <?php echo !$status ? 'btn-primary' : 'btn-secondary'; ?>">All</a>
            <a href="/admin/posts?status=published" class="btn btn-sm <?php echo $status === 'published' ? 'btn-primary' : 'btn-secondary'; ?>">Published</a>
            <a href="/admin/posts?status=draft" class="btn btn-sm <?php echo $status === 'draft' ? 'btn-primary' : 'btn-secondary'; ?>">Drafts</a>
        </div>

        <div class="admin-card" style="padding:0;overflow:hidden;">
            <?php if (empty($posts)): ?>
            <div style="text-align:center;padding:48px 24px;">
                <p style="color:var(--text-muted);margin:0 0 16px;">No posts found</p>
                <a href="/admin/posts/create" class="btn btn-primary btn-sm">Create Your First Post</a>
            </div>
            <?php else: ?>
            <div class="responsive-table">
                <table class="admin-table">
                    <thead>
                        <tr><th>Title</th><th>Category</th><th>Author</th><th>Status</th><th>Date</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $p): ?>
                        <tr>
                            <td style="font-weight:600;"><?php echo \App\Helpers\SEO::esc($p['title']); ?></td>
                            <td style="font-size:13px;"><?php echo \App\Helpers\SEO::esc($p['category_name'] ?? '—'); ?></td>
                            <td style="font-size:13px;"><?php echo \App\Helpers\SEO::esc($p['author']); ?></td>
                            <td>
                                <span class="badge" style="background:<?php echo $p['status'] === 'published' ? 'color-mix(in srgb, var(--success) 15%, transparent)' : 'color-mix(in srgb, var(--accent) 15%, transparent)'; ?>;color:<?php echo $p['status'] === 'published' ? 'var(--success)' : 'var(--accent)'; ?>;">
                                    <?php echo ucfirst($p['status']); ?>
                                </span>
                            </td>
                            <td style="font-size:13px;color:var(--text-muted);"><?php echo date('M j, Y', strtotime($p['created_at'])); ?></td>
                            <td>
                                <div style="display:flex;gap:4px;">
                                    <a href="/admin/posts/edit/<?php echo $p['id']; ?>" class="btn btn-sm btn-ghost" style="font-size:12px;">Edit</a>
                                    <form method="POST" action="/admin/posts/delete/<?php echo $p['id']; ?>" onsubmit="return confirm('Delete this post?');" style="display:inline;">
                                        <button type="submit" class="btn btn-sm btn-ghost" style="font-size:12px;color:var(--error);">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <?php if ($lastPage > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $lastPage; $i++): ?>
            <a href="/admin/posts?page=<?php echo $i; ?><?php echo $status ? "&status={$status}" : ''; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
