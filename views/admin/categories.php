<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories | PFSRV SEO Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="admin-body">
    <?php require __DIR__ . '/_nav.php'; ?>
    <div style="max-width:800px;margin:0 auto;padding:32px 24px;">
        <h1 style="font-size:1.75rem;margin:0 0 24px;">Categories</h1>

        <div class="admin-card" style="margin-bottom:24px;">
            <h2 style="font-size:1.1rem;margin:0 0 16px;">Add New Category</h2>
            <form method="POST">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-input" placeholder="Auto-generated">
                    </div>
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Add Category</button>
            </form>
        </div>

        <div class="admin-card" style="padding:0;overflow:hidden;">
            <div class="responsive-table">
                <table class="admin-table">
                    <thead><tr><th>Name</th><th>Slug</th><th>Posts</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td style="font-weight:600;"><?php echo \App\Helpers\SEO::esc($cat['name']); ?></td>
                            <td style="font-size:13px;color:var(--text-muted);">/blog/category/<?php echo \App\Helpers\SEO::esc($cat['slug']); ?></td>
                            <td><?php echo $cat['post_count']; ?></td>
                            <td>
                                <form method="POST" action="/admin/categories/delete/<?php echo $cat['id']; ?>" onsubmit="return confirm('Delete this category?');">
                                    <button type="submit" class="btn btn-sm btn-ghost" style="color:var(--error);font-size:12px;">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
