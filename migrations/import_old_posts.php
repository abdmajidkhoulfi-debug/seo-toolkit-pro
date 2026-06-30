<?php
/**
 * Import old blog_posts.json into the new database
 * Usage: php migrations/import_old_posts.php
 */

require_once __DIR__ . '/../src/autoload.php';

$config = require __DIR__ . '/../config/app.php';
$db = \App\Core\Database::getInstance($config['database']);
$pdo = $db->getPdo();

$jsonFile = __DIR__ . '/../admin/blog_posts.json';

if (!file_exists($jsonFile)) {
    echo "No blog_posts.json found to import.\n";
    exit(0);
}

$json = json_decode(file_get_contents($jsonFile), true);

if (empty($json)) {
    echo "No posts to import.\n";
    exit(0);
}

$stmt = $pdo->prepare(
    "INSERT OR IGNORE INTO posts (title, slug, content, excerpt, category_id, author, status, published_at, created_at, updated_at, featured)
     VALUES (?, ?, ?, ?, ?, ?, 'published', ?, ?, ?, 1)"
);

foreach ($json as $post) {
    if (($post['status'] ?? 'published') !== 'published') {
        continue;
    }

    $catName = $post['category'] ?? 'SEO Basics';
    $catStmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
    $catStmt->execute([$catName]);
    $cat = $catStmt->fetch(PDO::FETCH_ASSOC);

    $stmt->execute([
        $post['title'],
        $post['slug'],
        $post['content'],
        $post['excerpt'] ?? '',
        $cat ? $cat['id'] : null,
        $post['author'] ?? 'Admin',
        $post['created_at'] ?? date('Y-m-d H:i:s'),
        $post['created_at'] ?? date('Y-m-d H:i:s'),
        $post['updated_at'] ?? date('Y-m-d H:i:s'),
    ]);

    echo "Imported: {$post['title']}\n";
}

echo "\nImport complete! " . count($json) . " posts processed.\n";
