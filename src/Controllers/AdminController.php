<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Core\Database;
use App\Models\Setting;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\Subscriber;
use App\Helpers\SEO as SEOHelper;

class AdminController
{
    public function login(Request $request): void
    {
        try {
            if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
                View::redirect('/admin/dashboard');
                return;
            }

            // Allow re-setup via ?setup=1 query param
            if ($request->get('setup') === '1') {
                $db = Database::getInstance();
                $db->query("DELETE FROM users");
                View::redirect('/admin');
                return;
            }

            $config = require __DIR__ . '/../../config/app.php';
            $db = Database::getInstance();
            $hasUsers = $db->fetch("SELECT COUNT(*) as count FROM users")['count'] > 0;

            if ($request->getMethod() === 'POST') {
                $email = trim($request->post('email', ''));
                $password = $request->post('password', '');

                if (empty($email) || empty($password)) {
                    throw new \Exception('Please fill in all fields.');
                }

                if (!$hasUsers) {
                    // First-time setup
                    $name = trim($request->post('name', 'Admin'));
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $db->insert('users', [
                        'email' => $email,
                        'password_hash' => $hash,
                        'name' => $name,
                    ]);
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_email'] = $email;
                    $_SESSION['admin_name'] = $name;
                    View::redirect('/admin/dashboard');
                    return;
                }

                $user = $db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
                if ($user && password_verify($password, $user['password_hash'])) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_email'] = $email;
                    $_SESSION['admin_name'] = $user['name'];
                    $db->update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = ?', [$user['id']]);
                    View::redirect('/admin/dashboard');
                    return;
                }

                $error = 'Invalid email or password.';
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        View::render('admin/login', [
            'hasUsers' => $hasUsers ?? false,
            'error' => $error ?? null,
            '_no_layout' => true,
        ]);
    }

    public function dashboard(Request $request): void
    {
        $this->requireAuth();

        $db = Database::getInstance();

        $stats = [
            'posts' => $db->fetch("SELECT COUNT(*) as count FROM posts")['count'],
            'published' => $db->fetch("SELECT COUNT(*) as count FROM posts WHERE status = 'published'")['count'],
            'drafts' => $db->fetch("SELECT COUNT(*) as count FROM posts WHERE status = 'draft'")['count'],
            'categories' => $db->fetch("SELECT COUNT(*) as count FROM categories")['count'],
            'comments' => $db->fetch("SELECT COUNT(*) as count FROM comments")['count'],
            'pending_comments' => $db->fetch("SELECT COUNT(*) as count FROM comments WHERE status = 'pending'")['count'],
            'subscribers' => $db->fetch("SELECT COUNT(*) as count FROM subscribers WHERE active = 1")['count'],
            'total_views' => $db->fetch("SELECT COALESCE(SUM(view_count), 0) as total FROM posts")['total'],
        ];

        $recentPosts = $db->fetchAll("SELECT id, title, slug, status, created_at FROM posts ORDER BY created_at DESC LIMIT 5");
        $settings = Setting::getAll();

        View::render('admin/dashboard', [
            'stats' => $stats,
            'recentPosts' => $recentPosts,
            'settings' => $settings,
            '_no_layout' => true,
        ]);
    }

    public function saveSettings(Request $request): void
    {
        $this->requireAuth();

        $siteName = $request->post('site_name', 'PFSRV SEO');
        $siteDescription = $request->post('site_description', '');

        Setting::set('site_name', $siteName);
        Setting::set('site_description', $siteDescription);

        if ($request->post('header_code') !== null) {
            Setting::set('header_code', $request->post('header_code', ''));
        }
        if ($request->post('footer_code') !== null) {
            Setting::set('footer_code', $request->post('footer_code', ''));
        }

        View::redirect('/admin/dashboard?saved=1');
    }

    // ----- BLOG MANAGEMENT -----

    public function posts(Request $request): void
    {
        $this->requireAuth();

        $page = (int) $request->get('page', 1);
        $status = $request->get('status', '');

        $db = Database::getInstance();
        $conditions = [];
        $params = [];

        if ($status) {
            $conditions[] = 'p.status = ?';
            $params[] = $status;
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $total = $db->fetch(
            "SELECT COUNT(*) as count FROM posts p {$where}",
            $params
        )['count'];

        $posts = $db->fetchAll(
            "SELECT p.*, c.name as category_name
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             {$where}
             ORDER BY p.created_at DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [$perPage, $offset])
        );

        View::render('admin/posts', [
            'posts' => $posts,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => max(1, (int) ceil($total / $perPage)),
            'status' => $status,
            '_no_layout' => true,
        ]);
    }

    public function postEdit(Request $request, array $params): void
    {
        $this->requireAuth();

        $db = Database::getInstance();
        $postId = $params['id'] ?? null;

        $post = null;
        if ($postId) {
            $post = $db->fetch("SELECT * FROM posts WHERE id = ?", [$postId]);
            if ($post) {
                $post['tags'] = $db->fetchAll(
                    "SELECT t.* FROM tags t JOIN post_tags pt ON t.id = pt.tag_id WHERE pt.post_id = ?",
                    [$postId]
                );
            }
        }

        $categories = Category::getAll();
        $allTags = Tag::getAll();

        if ($request->getMethod() === 'POST') {
            $title = $request->post('title', '');
            $slug = $request->post('slug', '') ?: SEOHelper::slugify($title);
            $content = $request->post('content', '');
            $excerpt = $request->post('excerpt', '');
            $categoryId = $request->post('category_id') ? (int) $request->post('category_id') : null;
            $author = $request->post('author', 'Admin');
            $status = $request->post('status', 'draft');
            $metaTitle = $request->post('meta_title', '');
            $metaDescription = $request->post('meta_description', '');
            $featuredImage = $request->post('featured_image', '');
            $altText = $request->post('alt_text', '');
            $featured = $request->post('featured') ? 1 : 0;

            $tagInput = $request->post('tags', '');

            if (!$excerpt) {
                $excerpt = SEOHelper::truncate($content, 160);
            }

            $now = date('Y-m-d H:i:s');
            $publishedAt = $status === 'published' ? ($post ? $post['published_at'] ?? $now : $now) : null;

            $data = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'excerpt' => $excerpt,
                'category_id' => $categoryId,
                'author' => $author,
                'status' => $status,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDescription,
                'featured_image' => $featuredImage,
                'alt_text' => $altText,
                'featured' => $featured,
                'published_at' => $publishedAt,
                'updated_at' => $now,
            ];

            if ($postId) {
                $db->update('posts', $data, 'id = ?', [$postId]);
            } else {
                $data['created_at'] = $now;
                $postId = $db->insert('posts', $data);
            }

            // Handle tags
            if ($tagInput) {
                $tagNames = array_map('trim', explode(',', $tagInput));
                $tagIds = [];
                foreach ($tagNames as $name) {
                    if ($name) {
                        $tagIds[] = Tag::findOrCreate($name);
                    }
                }
                Tag::syncForPost($postId, $tagIds);
            }

            View::redirect('/admin/posts?saved=1');
            return;
        }

        View::render('admin/post-edit', [
            'post' => $post,
            'categories' => $categories,
            'allTags' => $allTags,
            '_no_layout' => true,
        ]);
    }

    public function postDelete(Request $request, array $params): void
    {
        $this->requireAuth();

        $id = $params['id'] ?? null;
        if ($id) {
            $db = Database::getInstance();
            $db->delete('posts', 'id = ?', [$id]);
        }

        View::redirect('/admin/posts');
    }

    // ----- CATEGORIES -----

    public function categories(Request $request): void
    {
        $this->requireAuth();

        $db = Database::getInstance();

        if ($request->getMethod() === 'POST') {
            $name = $request->post('name', '');
            $slug = $request->post('slug', '') ?: SEOHelper::slugify($name);
            $description = $request->post('description', '');

            if ($name) {
                $db->insert('categories', [
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                ]);
            }

            View::redirect('/admin/categories');
            return;
        }

        $categories = Category::getAll();
        View::render('admin/categories', [
            'categories' => $categories,
            '_no_layout' => true,
        ]);
    }

    public function categoryDelete(Request $request, array $params): void
    {
        $this->requireAuth();
        $id = $params['id'] ?? null;
        if ($id) {
            Category::delete((int) $id);
        }
        View::redirect('/admin/categories');
    }

    // ----- COMMENTS -----

    public function comments(Request $request): void
    {
        $this->requireAuth();

        $page = (int) $request->get('page', 1);
        $result = Comment::getPending($page);

        View::render('admin/comments', [
            'comments' => $result['comments'],
            'total' => $result['total'],
            'page' => $result['page'],
            'lastPage' => $result['lastPage'],
            '_no_layout' => true,
        ]);
    }

    public function commentApprove(Request $request, array $params): void
    {
        $this->requireAuth();
        Comment::approve((int) ($params['id'] ?? 0));
        View::redirect('/admin/comments');
    }

    public function commentDelete(Request $request, array $params): void
    {
        $this->requireAuth();
        Comment::delete((int) ($params['id'] ?? 0));
        View::redirect('/admin/comments');
    }

    // ----- SUBSCRIBERS -----

    public function subscribers(Request $request): void
    {
        $this->requireAuth();
        $subscribers = Subscriber::getAll();
        View::render('admin/subscribers', [
            'subscribers' => $subscribers,
            '_no_layout' => true,
        ]);
    }

    // ----- MEDIA -----

    public function media(Request $request): void
    {
        $this->requireAuth();

        $db = Database::getInstance();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $uploadPath = __DIR__ . '/../../storage/media/' . $filename;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $db->insert('media', [
                    'filename' => $filename,
                    'original_name' => $file['name'],
                    'mime_type' => $file['type'],
                    'size' => $file['size'],
                ]);
            }

            View::redirect('/admin/media');
            return;
        }

        $media = $db->fetchAll("SELECT * FROM media ORDER BY uploaded_at DESC");
        View::render('admin/media', [
            'media' => $media,
            '_no_layout' => true,
        ]);
    }

    // ----- LOGOUT -----

    public function logout(): void
    {
        session_destroy();
        View::redirect('/admin');
    }

    private function requireAuth(): void
    {
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            View::redirect('/admin');
            exit;
        }
    }
}
