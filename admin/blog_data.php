<?php
// Blog posts data storage
// This file stores all blog posts in a serialized PHP array

$blog_data_file = __DIR__ . '/blog_posts.json';

// Default empty posts array
$blog_posts = [];

// Load existing posts
if (file_exists($blog_data_file)) {
    $json = file_get_contents($blog_data_file);
    $decoded = json_decode($json, true);
    if (is_array($decoded)) {
        $blog_posts = $decoded;
    }
}

/**
 * Get all blog posts, sorted by date (newest first)
 */
function getBlogPosts(): array {
    global $blog_posts;
    
    // Sort by created_at descending
    $sorted = $blog_posts;
    usort($sorted, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    
    return $sorted;
}

/**
 * Get a single blog post by ID
 */
function getBlogPost(string $id): ?array {
    global $blog_posts;
    
    foreach ($blog_posts as $post) {
        if ($post['id'] === $id) {
            return $post;
        }
    }
    
    return null;
}

/**
 * Get featured / latest post
 */
function getFeaturedPost(): ?array {
    $posts = getBlogPosts();
    return $posts[0] ?? null;
}

/**
 * Get recent posts (limited)
 */
function getRecentPosts(int $limit = 5): array {
    $posts = getBlogPosts();
    return array_slice($posts, 0, $limit);
}

/**
 * Get category counts
 */
function getCategoryCounts(): array {
    $posts = getBlogPosts();
    $counts = [];
    
    foreach ($posts as $post) {
        $cat = $post['category'] ?: 'General';
        $counts[$cat] = ($counts[$cat] ?? 0) + 1;
    }
    
    arsort($counts);
    return $counts;
}

/**
 * Save all blog posts to JSON file
 */
function saveBlogPosts(array $posts): bool {
    global $blog_data_file;
    
    // Re-index
    $posts = array_values($posts);
    
    $json = json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return file_put_contents($blog_data_file, $json) !== false;
}

/**
 * Create a new blog post
 */
function createBlogPost(array $data): array {
    global $blog_posts;
    
    $post = [
        'id' => uniqid('post_', true),
        'title' => trim($data['title']),
        'slug' => generateSlug(trim($data['title'])),
        'content' => $data['content'] ?? '',
        'excerpt' => trim($data['excerpt'] ?? ''),
        'category' => trim($data['category'] ?? 'General'),
        'author' => trim($data['author'] ?? 'Admin'),
        'image' => trim($data['image'] ?? ''),
        'status' => $data['status'] ?? 'published',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    // Auto-generate excerpt from content if not provided
    if (empty($post['excerpt']) && !empty($post['content'])) {
        $text = trim(strip_tags($post['content']));
        $post['excerpt'] = strlen($text) > 160 ? substr($text, 0, 157) . '...' : $text;
    }
    
    $blog_posts[] = $post;
    saveBlogPosts($blog_posts);
    
    return $post;
}

/**
 * Update an existing blog post
 */
function updateBlogPost(string $id, array $data): ?array {
    global $blog_posts;
    
    foreach ($blog_posts as &$post) {
        if ($post['id'] === $id) {
            if (isset($data['title'])) {
                $post['title'] = trim($data['title']);
                $post['slug'] = generateSlug(trim($data['title']));
            }
            if (isset($data['content'])) {
                $post['content'] = $data['content'];
            }
            if (isset($data['excerpt'])) {
                $post['excerpt'] = trim($data['excerpt']);
            }
            if (isset($data['category'])) {
                $post['category'] = trim($data['category']);
            }
            if (isset($data['author'])) {
                $post['author'] = trim($data['author']);
            }
            if (isset($data['image'])) {
                $post['image'] = trim($data['image']);
            }
            if (isset($data['status'])) {
                $post['status'] = $data['status'];
            }
            
            // Auto-generate excerpt if empty
            if (empty($post['excerpt']) && !empty($post['content'])) {
                $text = trim(strip_tags($post['content']));
                $post['excerpt'] = strlen($text) > 160 ? substr($text, 0, 157) . '...' : $text;
            }
            
            $post['updated_at'] = date('Y-m-d H:i:s');
            
            saveBlogPosts($blog_posts);
            return $post;
        }
    }
    
    return null;
}

/**
 * Delete a blog post
 */
function deleteBlogPost(string $id): bool {
    global $blog_posts;
    
    foreach ($blog_posts as $key => $post) {
        if ($post['id'] === $id) {
            array_splice($blog_posts, $key, 1);
            saveBlogPosts($blog_posts);
            return true;
        }
    }
    
    return false;
}

/**
 * Generate URL-friendly slug
 */
function generateSlug(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    $text = trim($text, '-');
    
    // Add unique suffix if empty
    if (empty($text)) {
        $text = 'post-' . substr(uniqid(), -6);
    }
    
    return $text;
}

/**
 * Format date for display
 */
function formatBlogDate(string $date): string {
    $timestamp = strtotime($date);
    return $timestamp ? date('M d, Y', $timestamp) : $date;
}

/**
 * Calculate read time in minutes
 */
function blogReadTime(string $content): int {
    $text = trim(strip_tags($content));
    $words = str_word_count($text);
    return max(1, (int) ceil($words / 220));
}

/**
 * Escapes HTML safely
 */
function esc(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
