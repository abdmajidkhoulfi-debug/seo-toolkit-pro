<?php
require_once 'config.php';
require_once 'blog_data.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$message = '';
$error = '';

$action = $_GET['action'] ?? 'list';
$editPost = null;

// Handle Delete
if ($action === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (deleteBlogPost($id)) {
        $message = '✅ Blog post deleted successfully!';
    } else {
        $error = '❌ Blog post not found.';
    }
    $action = 'list';
}

// Handle Create / Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_post'])) {
        $title = trim($_POST['title']);
        if (empty($title)) {
            $error = '❌ Title is required.';
        } else {
            $post = createBlogPost([
                'title' => $title,
                'content' => $_POST['content'] ?? '',
                'excerpt' => $_POST['excerpt'] ?? '',
                'category' => $_POST['category'] ?? 'General',
                'author' => $_POST['author'] ?? 'Admin',
                'image' => $_POST['image'] ?? '',
                'status' => $_POST['status'] ?? 'published'
            ]);
            $message = '✅ Blog post "' . esc($post['title']) . '" created!';
            $action = 'list';
        }
    } elseif (isset($_POST['update_post']) && isset($_POST['post_id'])) {
        $id = $_POST['post_id'];
        $result = updateBlogPost($id, [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'excerpt' => $_POST['excerpt'] ?? '',
            'category' => $_POST['category'] ?? 'General',
            'author' => $_POST['author'] ?? 'Admin',
            'image' => $_POST['image'] ?? '',
            'status' => $_POST['status'] ?? 'published'
        ]);
        if ($result) {
            $message = '✅ Blog post "' . esc($result['title']) . '" updated!';
            $action = 'list';
        } else {
            $error = '❌ Could not update post.';
        }
    }
}

// Load post for editing
if ($action === 'edit' && isset($_GET['id'])) {
    $editPost = getBlogPost($_GET['id']);
    if (!$editPost) {
        $error = '❌ Blog post not found.';
        $action = 'list';
    }
}

$posts = getBlogPosts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Manager - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            color: #1a2634;
        }
        .container { max-width: 960px; margin: 0 auto; padding: 40px 20px; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .header h1 { font-size: 28px; font-weight: 700; }
        .header-actions { display: flex; gap: 10px; }
        .btn {
            padding: 10px 20px;
            border-radius: 999px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            border: none;
        }
        .btn-primary {
            background: #0057ff;
            color: white;
        }
        .btn-primary:hover { background: #0047d1; }
        .btn-secondary {
            background: white;
            color: #374151;
            border: 1px solid #e5e7eb;
        }
        .btn-secondary:hover { background: #f9fafb; }
        .btn-danger {
            background: #dc2626;
            color: white;
        }
        .btn-danger:hover { background: #b91c1c; }
        .btn-sm { padding: 6px 14px; font-size: 13px; }
        .card {
            background: white;
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
        }
        .card h2 {
            font-size: 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .message {
            background: #d1fae5;
            color: #065f46;
            padding: 14px 20px;
            border-radius: 16px;
            margin-bottom: 24px;
        }
        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 14px 20px;
            border-radius: 16px;
            margin-bottom: 24px;
        }
        .form-group { margin-bottom: 20px; }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 13px;
            color: #374151;
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
        }
        textarea { min-height: 120px; resize: vertical; }
        .editor-area { min-height: 300px; font-family: monospace; }
        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { font-size: 12px; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; }
        td { vertical-align: middle; }
        .post-title { font-weight: 600; }
        .post-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-published { background: #d1fae5; color: #065f46; }
        .status-draft { background: #fef3c7; color: #92400e; }
        .action-links { display: flex; gap: 8px; }
        .action-links a { font-size: 13px; text-decoration: none; font-weight: 600; }
        .action-links .edit-link { color: #0057ff; }
        .action-links .delete-link { color: #dc2626; }
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #6b7280;
        }
        .empty-state h3 { font-size: 18px; margin-bottom: 8px; color: #374151; }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #6b7280;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .back-link:hover { color: #374151; }
        .char-count { font-size: 12px; color: #9ca3af; text-align: right; margin-top: 4px; }
        @media (max-width: 640px) {
            .row-2 { grid-template-columns: 1fr; }
            .header { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($action === 'list'): ?>
            <div class="header">
                <h1>📝 Blog Manager</h1>
                <div class="header-actions">
                    <a href="dashboard.php" class="btn btn-secondary">← Dashboard</a>
                    <a href="?action=create" class="btn btn-primary">+ New Post</a>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="message"><?php echo esc($message); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="error"><?php echo esc($error); ?></div>
            <?php endif; ?>

            <div class="card">
                <h2>📋 All Blog Posts (<?php echo count($posts); ?>)</h2>

                <?php if ($posts): ?>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <td>
                                            <span class="post-title"><?php echo esc($post['title']); ?></span>
                                            <?php if ($post['excerpt']): ?>
                                                <br><span style="font-size:12px;color:#9ca3af;"><?php echo esc(substr($post['excerpt'], 0, 80)); ?><?php echo strlen($post['excerpt']) > 80 ? '...' : ''; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="post-chip" style="color:#0057ff;"><?php echo esc($post['category'] ?: 'General'); ?></span></td>
                                        <td><?php echo esc($post['author']); ?></td>
                                        <td style="white-space:nowrap;"><?php echo formatBlogDate($post['created_at']); ?></td>
                                        <td>
                                            <span class="post-status status-<?php echo esc($post['status']); ?>">
                                                <?php echo esc(ucfirst($post['status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-links">
                                                <a class="edit-link" href="?action=edit&id=<?php echo urlencode($post['id']); ?>">Edit</a>
                                                <a class="delete-link" href="?action=delete&id=<?php echo urlencode($post['id']); ?>" onclick="return confirm('Delete this post?')">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>No blog posts yet</h3>
                        <p>Create your first blog post to get started.</p>
                        <a href="?action=create" class="btn btn-primary" style="margin-top:16px;">+ Create First Post</a>
                    </div>
                <?php endif; ?>
            </div>

        <?php elseif ($action === 'create' || ($action === 'edit' && $editPost)): ?>
            <a href="?action=list" class="back-link">← Back to blog list</a>

            <div class="card">
                <h2><?php echo $action === 'create' ? '✏️ Create New Post' : '✏️ Edit Post'; ?></h2>

                <?php if ($error): ?>
                    <div class="error"><?php echo esc($error); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <?php if ($action === 'edit' && $editPost): ?>
                        <input type="hidden" name="post_id" value="<?php echo esc($editPost['id']); ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label>📰 Post Title *</label>
                        <input type="text" name="title" required
                               value="<?php echo $editPost ? esc($editPost['title']) : ''; ?>"
                               placeholder="Enter a compelling title...">
                    </div>

                    <div class="row-2">
                        <div class="form-group">
                            <label>📂 Category</label>
                            <input type="text" name="category"
                                   value="<?php echo $editPost ? esc($editPost['category']) : 'General'; ?>"
                                   placeholder="e.g., SEO Tips, Guides, News">
                        </div>
                        <div class="form-group">
                            <label>✍️ Author</label>
                            <input type="text" name="author"
                                   value="<?php echo $editPost ? esc($editPost['author']) : 'Admin'; ?>"
                                   placeholder="Author name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>🖼️ Image URL (optional)</label>
                        <input type="text" name="image"
                               value="<?php echo $editPost ? esc($editPost['image']) : ''; ?>"
                               placeholder="https://example.com/image.jpg">
                    </div>

                    <div class="row-2">
                        <div class="form-group">
                            <label>📝 Excerpt (optional - auto-generated if empty)</label>
                            <textarea name="excerpt" maxlength="300" oninput="updateCharCount(this)"><?php echo $editPost ? esc($editPost['excerpt']) : ''; ?></textarea>
                            <div class="char-count"><span id="charCount">0</span>/300</div>
                        </div>
                        <div class="form-group">
                            <label>📌 Status</label>
                            <select name="status">
                                <option value="published" <?php echo ($editPost && $editPost['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo ($editPost && $editPost['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>📄 Content (HTML supported)</label>
                        <textarea name="content" class="editor-area" placeholder="Write your blog post content here..."><?php echo $editPost ? esc($editPost['content']) : ''; ?></textarea>
                    </div>

                    <button type="submit" name="<?php echo $action === 'create' ? 'create_post' : 'update_post'; ?>" class="btn btn-primary">
                        <?php echo $action === 'create' ? '📌 Publish Post' : '💾 Save Changes'; ?>
                    </button>
                    <a href="?action=list" class="btn btn-secondary" style="margin-left:8px;">Cancel</a>
                </form>
            </div>

            <script>
                function updateCharCount(el) {
                    document.getElementById('charCount').textContent = el.value.length;
                }
                document.addEventListener('DOMContentLoaded', function() {
                    const el = document.querySelector('textarea[name="excerpt"]');
                    if (el) updateCharCount(el);
                });
            </script>
        <?php endif; ?>
    </div>
</body>
</html>
