<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post ? 'Edit Post' : 'Create Post'; ?> | PFSRV SEO Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="admin-body">
    <?php require __DIR__ . '/_nav.php'; ?>
    <div style="max-width:900px;margin:0 auto;padding:32px 24px;">
        <h1 style="font-size:1.75rem;margin:0 0 32px;"><?php echo $post ? 'Edit Post' : 'Create New Post'; ?></h1>

        <form method="POST" class="admin-card">
            <div style="margin-bottom:16px;">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-input" required value="<?php echo \App\Helpers\SEO::esc($post['title'] ?? ''); ?>" oninput="updateSlug(this.value)">
            </div>

            <div style="margin-bottom:16px;">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-input" id="postSlug" value="<?php echo \App\Helpers\SEO::esc($post['slug'] ?? ''); ?>" placeholder="Auto-generated from title">
                <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">Leave empty to auto-generate from title</div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">— No category —</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($post['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>><?php echo \App\Helpers\SEO::esc($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?php echo ($post['status'] ?? 'draft') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo ($post['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label class="form-label">Content *</label>
                <textarea name="content" class="form-textarea" rows="20" style="font-family:monospace;font-size:14px;line-height:1.7;min-height:400px;"><?php echo \App\Helpers\SEO::esc($post['content'] ?? ''); ?></textarea>
            </div>

            <div style="margin-bottom:16px;">
                <label class="form-label">Excerpt</label>
                <textarea name="excerpt" class="form-textarea" rows="3"><?php echo \App\Helpers\SEO::esc($post['excerpt'] ?? ''); ?></textarea>
                <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">Auto-generated from content if left empty</div>
            </div>

            <h2 style="font-size:1.1rem;margin:24px 0 16px;">SEO Settings</h2>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-input" value="<?php echo \App\Helpers\SEO::esc($post['meta_title'] ?? ''); ?>" maxlength="70">
                </div>
                <div>
                    <label class="form-label">Meta Description</label>
                    <input type="text" name="meta_description" class="form-input" value="<?php echo \App\Helpers\SEO::esc($post['meta_description'] ?? ''); ?>" maxlength="320">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Featured Image URL</label>
                    <input type="url" name="featured_image" class="form-input" value="<?php echo \App\Helpers\SEO::esc($post['featured_image'] ?? ''); ?>" placeholder="https://...">
                </div>
                <div>
                    <label class="form-label">Image Alt Text</label>
                    <input type="text" name="alt_text" class="form-input" value="<?php echo \App\Helpers\SEO::esc($post['alt_text'] ?? ''); ?>">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Author</label>
                    <input type="text" name="author" class="form-input" value="<?php echo \App\Helpers\SEO::esc($post['author'] ?? 'Admin'); ?>">
                </div>
                <div>
                    <label class="form-label">Tags (comma separated)</label>
                    <input type="text" name="tags" class="form-input" value="<?php echo \App\Helpers\SEO::esc(implode(', ', array_map(fn($t) => $t['name'], $post['tags'] ?? []))); ?>" placeholder="seo, marketing, tips">
                </div>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="featured" value="1" <?php echo !empty($post['featured']) ? 'checked' : ''; ?>>
                    <span style="font-size:14px;font-weight:600;">Feature this post (show on homepage)</span>
                </label>
            </div>

            <div style="display:flex;gap:12px;">
                <button type="submit" class="btn btn-primary"><?php echo $post ? 'Update Post' : 'Create Post'; ?></button>
                <a href="/admin/posts" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
    function updateSlug(title) {
        const slug = document.getElementById('postSlug');
        if (!slug.value || slug.dataset.auto === 'true' || !slug.dataset.auto) {
            slug.value = title.toLowerCase()
                .replace(/[^a-z0-9-]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            slug.dataset.auto = slug.value ? 'true' : 'false';
        }
    }
    document.getElementById('postSlug')?.addEventListener('input', function() {
        this.dataset.auto = 'false';
    });
    </script>
</body>
</html>
