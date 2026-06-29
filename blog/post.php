<?php
declare(strict_types=1);

require_once __DIR__ . '/../admin/blog_data.php';

$siteName = 'SEO Toolkit Pro';

// Get the post by slug
$slug = $_GET['slug'] ?? '';

// Find the post
$allPosts = getBlogPosts();
$post = null;
foreach ($allPosts as $p) {
    if ($p['slug'] === $slug && ($p['status'] ?? 'published') === 'published') {
        $post = $p;
        break;
    }
}

// If not found, 404
if (!$post) {
    header('HTTP/1.0 404 Not Found');
    $notFound = true;
} else {
    $notFound = false;
    $pageTitle = $post['title'] . ' – ' . $siteName;
    $pageDescription = $post['excerpt'];
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $notFound ? 'Post Not Found' : esc($pageTitle) ?></title>
    <?php if (!$notFound): ?>
    <meta name="description" content="<?= esc($pageDescription) ?>">
    <meta name="author" content="<?= esc($post['author']) ?>">
    <?php if ($post['image']): ?>
    <meta property="og:image" content="<?= esc($post['image']) ?>">
    <?php endif; ?>
    <meta property="og:title" content="<?= esc($post['title']) ?>">
    <meta property="og:description" content="<?= esc($pageDescription) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <?php endif; ?>
    <meta name="robots" content="<?= $notFound ? 'noindex' : 'index,follow' ?>">
    <link rel="preconnect" href="https://api.fontshare.com">
    <link href="https://api.fontshare.com/v2/css?f[]=general-sans@400,500,600,700&f[]=cabinet-grotesk@500,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
    <style>
        .post-article {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .post-article .post-header {
            margin-bottom: 32px;
        }
        .post-article .post-header h1 {
            font-family: 'Cabinet Grotesk', sans-serif;
            font-size: clamp(2rem, 5vw, 3.2rem);
            letter-spacing: -0.04em;
            line-height: 1.1;
            margin-bottom: 16px;
        }
        .post-article .post-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            color: var(--muted);
            font-size: 15px;
            margin-bottom: 24px;
        }
        .post-article .post-meta .chip {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary);
            font-weight: 600;
            font-size: 13px;
        }
        .post-article .featured-image {
            width: 100%;
            border-radius: var(--radius-lg);
            margin-bottom: 32px;
            aspect-ratio: 16/9;
            object-fit: cover;
        }
        .post-article .post-body {
            font-size: 17px;
            line-height: 1.8;
            color: var(--text);
        }
        .post-article .post-body h2 {
            font-family: 'Cabinet Grotesk', sans-serif;
            font-size: 1.6rem;
            margin-top: 36px;
            margin-bottom: 12px;
            letter-spacing: -0.03em;
        }
        .post-article .post-body h3 {
            font-family: 'Cabinet Grotesk', sans-serif;
            font-size: 1.25rem;
            margin-top: 28px;
            margin-bottom: 10px;
        }
        .post-article .post-body p {
            margin-bottom: 16px;
        }
        .post-article .post-body ul, 
        .post-article .post-body ol {
            margin-bottom: 16px;
            padding-left: 24px;
        }
        .post-article .post-body li {
            margin-bottom: 6px;
        }
        .post-article .post-body a {
            color: var(--primary);
            text-decoration: underline;
        }
        .post-article .post-body blockquote {
            border-left: 4px solid var(--primary);
            padding: 16px 20px;
            margin: 20px 0;
            background: var(--primary-soft);
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
            font-style: italic;
        }
        .post-article .post-body img {
            max-width: 100%;
            border-radius: var(--radius-md);
            margin: 20px 0;
        }
        .post-article .post-body pre {
            background: var(--surface-2);
            padding: 20px;
            border-radius: var(--radius-md);
            overflow-x: auto;
            font-size: 14px;
            margin: 20px 0;
        }
        .post-article .post-body code {
            background: var(--surface-2);
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 14px;
        }
        .post-article .post-body pre code {
            background: none;
            padding: 0;
        }
        .post-nav {
            margin-top: 48px;
            padding-top: 24px;
            border-top: 1px solid var(--line);
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .post-nav a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }
        .post-nav a:hover {
            text-decoration: underline;
        }
        .not-found {
            text-align: center;
            padding: 80px 20px;
        }
        .not-found h1 {
            font-size: 3rem;
            margin-bottom: 12px;
        }
        .not-found p {
            color: var(--muted);
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to content</a>

    <header class="site-header" id="siteHeader">
        <div class="container header-inner">
            <a href="../index.html" class="brand" aria-label="SEO Toolkit Pro home">
                <span class="brand-mark" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M4 17L10 11L14 15L20 8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M16 8H20V12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
                <span class="brand-name"><?= esc($siteName) ?></span>
            </a>
            <nav class="desktop-nav" aria-label="Primary navigation">
                <a href="../index.html">Home</a>
                <a href="../blog/index.php" class="active">Blog</a>
            </nav>
            <div class="header-actions">
                <button class="icon-btn" id="themeToggle" type="button" aria-label="Toggle theme">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3A7 7 0 0 0 21 12.79Z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <main id="main-content">
        <?php if ($notFound): ?>
            <div class="container not-found">
                <h1>404</h1>
                <p>Sorry, the blog post you're looking for doesn't exist or has been removed.</p>
                <a href="index.php" class="btn btn-primary">← Back to Blog</a>
            </div>
        <?php else: ?>
            <article class="post-article">
                <div class="post-header">
                    <h1><?= esc($post['title']) ?></h1>
                    <div class="post-meta">
                        <span class="chip"><?= esc($post['category'] ?: 'General') ?></span>
                        <span><?= formatBlogDate($post['created_at']) ?></span>
                        <span>·</span>
                        <span><?= esc((string) blogReadTime($post['content'])) ?> min read</span>
                        <span>·</span>
                        <span>By <?= esc($post['author']) ?></span>
                    </div>
                </div>

                <?php if ($post['image']): ?>
                    <img class="featured-image" src="<?= esc($post['image']) ?>" alt="<?= esc($post['title']) ?>" loading="lazy">
                <?php endif; ?>

                <div class="post-body">
                    <?= $post['content'] ?>
                </div>

                <div class="post-nav">
                    <a href="index.php">← Back to Blog</a>
                    <a href="../index.html">Home</a>
                </div>
            </article>
        <?php endif; ?>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <p>© 2026 <?= esc($siteName) ?>. SEO articles, guides and tutorials.</p>
            <p>Built for readers, structured for discovery.</p>
        </div>
    </footer>

    <script src="./script.js"></script>
</body>
</html>
