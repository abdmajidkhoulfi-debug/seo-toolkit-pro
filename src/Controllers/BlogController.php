<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use App\Services\SEOService;
use App\Helpers\SEO;

class BlogController
{
    public function index(Request $request): void
    {
        $page = (int) $request->get('page', 1);
        $category = $request->get('category', '');
        $search = $request->get('search', '');
        $tag = $request->get('tag', '');

        if ($category) {
            $result = Post::getByCategory($category, $page);
            $seo = SEOService::categoryPage(
                $result['category']['name'] ?? $category,
                $result['category']['description'] ?? '',
                $page
            );
        } elseif ($tag) {
            $result = Post::getByTag($tag, $page);
            $seo = SEOService::page([
                'title' => ($result['tag']['name'] ?? $tag) . ($page > 1 ? " - Page {$page}" : ''),
                'description' => "Browse all articles tagged with {$tag}.",
                'url' => (Setting::get('site_url', '')) . "/blog?tag={$tag}" . ($page > 1 ? "&page={$page}" : ''),
            ]);
        } elseif ($search) {
            $result = Post::search($search, $page);
            $seo = SEOService::page([
                'title' => "Search: {$search}" . ($page > 1 ? " - Page {$page}" : ''),
                'description' => "Search results for: {$search}",
                'url' => (Setting::get('site_url', '')) . "/blog?search=" . urlencode($search) . ($page > 1 ? "&page={$page}" : ''),
                'robots' => 'noindex, follow',
            ]);
        } else {
            $result = Post::getPublished($page);
            $seo = SEOService::blogIndex($page);
        }

        $categories = Category::getAll();
        $recentPosts = Post::getRecent(5);

        View::share('seo', $seo);
        View::render('blog/index', [
            'posts' => $result['posts'],
            'total' => $result['total'],
            'page' => $result['page'],
            'perPage' => $result['perPage'],
            'lastPage' => $result['lastPage'],
            'categories' => $categories,
            'recentPosts' => $recentPosts,
            'currentCategory' => $category,
            'currentTag' => $tag,
            'searchQuery' => $search,
            'layout' => 'main',
        ]);
    }

    public function show(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';
        $post = Post::findBySlug($slug);

        if (!$post) {
            View::notFound();
            return;
        }

        Post::incrementViews($post['id']);

        $seo = SEOService::blogPost($post);
        $categories = Category::getAll();
        $recentPosts = Post::getRecent(5, $post['id']);
        $relatedPosts = Post::getRelated($post['id'], $post['category_id'], 3);
        $comments = Comment::getForPost($post['id']);
        $readingTime = SEO::readingTime($post['content']);

        View::share('seo', $seo);
        View::render('blog/post', [
            'post' => $post,
            'readingTime' => $readingTime,
            'relatedPosts' => $relatedPosts,
            'recentPosts' => $recentPosts,
            'categories' => $categories,
            'comments' => $comments,
            'layout' => 'main',
        ]);
    }

    public function comment(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';
        $post = Post::findBySlug($slug);

        if (!$post) {
            View::json(['success' => false, 'message' => 'Post not found.'], 404);
            return;
        }

        $name = trim($request->post('name', ''));
        $email = trim($request->post('email', ''));
        $content = trim($request->post('content', ''));

        if (!$name || !$email || !$content) {
            View::json(['success' => false, 'message' => 'All fields are required.'], 400);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            View::json(['success' => false, 'message' => 'Please enter a valid email address.'], 400);
            return;
        }

        $parentId = $request->post('parent_id') ? (int) $request->post('parent_id') : null;
        $website = trim($request->post('website', ''));

        Comment::create([
            'post_id' => $post['id'],
            'parent_id' => $parentId,
            'name' => $name,
            'email' => $email,
            'website' => $website,
            'content' => nl2br(htmlspecialchars($content)),
            'status' => 'pending',
        ]);

        View::json([
            'success' => true,
            'message' => 'Your comment has been submitted and is awaiting moderation.',
        ]);
    }
}
