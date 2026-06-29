<?php
declare(strict_types=1);

require_once __DIR__ . '/../admin/blog_data.php';

$blogDir = __DIR__;
$siteName = 'SEO Toolkit Pro';
$pageTitle = 'SEO Blog';
$pageDescription = 'Practical SEO guides, technical tutorials, indexing tips, and search growth articles from SEO Toolkit Pro.';

function formatDateHuman(string $date): string {
    $timestamp = strtotime($date);
    return $timestamp ? date('M d, Y', $timestamp) : $date;
}

function readTimeMinutes(string $html): int {
    $text = trim(strip_tags($html));
    $words = str_word_count($text);
    return max(1, (int) ceil($words / 220));
}

// Get all posts from the database
$allPosts = getBlogPosts();

// Filter to only published posts
$posts = array_filter($allPosts, function($p) {
    return ($p['status'] ?? 'published') === 'published';
});

// Re-index
$posts = array_values($posts);

$featured = $posts[0] ?? null;
$recentPosts = array_slice($posts, 0, 5);

$categoryCounts = getCategoryCounts();
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle) ?> – <?= esc($siteName) ?></title>
    <meta name="description" content="<?= esc($pageDescription) ?>">
    <meta name="robots" content="index,follow">
    <link rel="preconnect" href="https://api.fontshare.com">
    <link href="https://api.fontshare.com/v2/css?f[]=general-sans@400,500,600,700&f[]=cabinet-grotesk@500,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
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
                <a href="../seo-analyzer/index.html">SEO Analyzer</a>
                <a href="../robots-txt-generator/index.html">Robots Generator</a>
                <a href="../hreflang-generator/index.html">Hreflang Generator</a>
                <a href="./index.php" class="active">Blog</a>
            </nav>

            <div class="header-actions">
                <button class="icon-btn" id="themeToggle" type="button" aria-label="Toggle theme">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3A7 7 0 0 0 21 12.79Z"></path>
                    </svg>
                </button>
                <button class="menu-btn" id="menuToggle" type="button" aria-label="Open mobile menu" aria-expanded="false">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M3 6H21M3 12H21M3 18H21" stroke-linecap="round"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <aside class="mobile-nav" id="mobileNav" aria-hidden="true">
        <div class="mobile-nav-backdrop" id="mobileBackdrop"></div>
        <div class="mobile-nav-panel" role="dialog" aria-modal="true" aria-label="Mobile menu">
            <div class="mobile-top">
                <strong>Navigation</strong>
                <button class="icon-btn" id="menuClose" type="button" aria-label="Close mobile menu">✕</button>
            </div>
            <nav class="mobile-links" aria-label="Mobile navigation">
                <a href="../index.html">Home</a>
                <a href="../seo-analyzer/index.html">SEO Analyzer</a>
                <a href="../robots-txt-generator/index.html">Robots Generator</a>
                <a href="../hreflang-generator/index.html">Hreflang Generator</a>
                <a href="./index.php" class="active">Blog</a>
            </nav>
        </div>
    </aside>

    <main id="main-content" class="container">
        <section class="blog-hero">
            <div class="blog-hero-grid">
                <div class="hero-main">
                    <span class="eyebrow">Learning Center</span>
                    <h1>SEO insights, indexing guides and practical growth content</h1>
                    <p>
                        Explore technical SEO tutorials, blog optimization tips, crawling and indexing guides, and actionable strategies for growing search visibility.
                    </p>

                    <div class="hero-stats">
                        <div class="hero-stat">
                            <strong><?= esc((string) count($posts)) ?></strong>
                            <span>Published posts</span>
                        </div>
                        <div class="hero-stat">
                            <strong><?= esc((string) count($categoryCounts)) ?></strong>
                            <span>Categories</span>
                        </div>
                        <div class="hero-stat">
                            <strong><?= esc($featured ? formatBlogDate($featured['created_at']) : '—') ?></strong>
                            <span>Latest update</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php if ($featured): ?>
        <section class="featured-section">
            <div class="section-head">
                <h2>Featured article</h2>
                <p>The latest post from your blog collection.</p>
            </div>

            <article class="featured-post">
                <?php if ($featured['image'] !== ''): ?>
                    <a class="featured-image-wrap" href="post.php?slug=<?= urlencode($featured['slug']) ?>">
                        <img src="<?= esc($featured['image']) ?>" alt="<?= esc($featured['title']) ?>" width="1200" height="675" loading="lazy">
                    </a>
                <?php endif; ?>

                <div class="featured-content">
                    <div class="post-meta-row">
                        <span class="post-chip"><?= esc($featured['category']) ?></span>
                        <span><?= formatBlogDate($featured['created_at']) ?></span>
                        <span><?= esc((string) blogReadTime($featured['content'])) ?> min read</span>
                    </div>
                    <h3><a href="post.php?slug=<?= urlencode($featured['slug']) ?>"><?= esc($featured['title']) ?></a></h3>
                    <p><?= esc($featured['excerpt']) ?></p>
                    <div class="post-footer-row">
                        <span>By <?= esc($featured['author']) ?></span>
                        <a class="read-link" href="post.php?slug=<?= urlencode($featured['slug']) ?>">Read article</a>
                    </div>
                </div>
            </article>
        </section>
        <?php endif; ?>

        <section class="blog-layout">
            <div class="content-column">
                <div class="section-head">
                    <h2>Latest posts</h2>
                    <p>Fresh articles from the blog, sorted by newest first.</p>
                </div>

                <div class="toolbar">
                    <div class="search-wrap">
                        <input type="text" id="postSearch" placeholder="Search blog posts">
                    </div>

                    <div class="filter-wrap">
                        <select id="categoryFilter">
                            <option value="all">All categories</option>
                            <?php foreach ($categoryCounts as $category => $count): ?>
                                <option value="<?= esc(strtolower($category)) ?>"><?= esc($category) ?> (<?= esc((string) $count) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <?php if ($posts): ?>
                    <div class="posts-grid" id="postsGrid">
                        <?php foreach ($posts as $post): ?>
                            <article
                                class="post-card"
                                data-title="<?= esc(strtolower($post['title'])) ?>"
                                data-excerpt="<?= esc(strtolower($post['excerpt'])) ?>"
                                data-category="<?= esc(strtolower($post['category'])) ?>"
                                data-author="<?= esc(strtolower($post['author'])) ?>"
                            >
                                <?php if ($post['image'] !== ''): ?>
                                    <a class="post-image-wrap" href="post.php?slug=<?= urlencode($post['slug']) ?>">
                                        <img src="<?= esc($post['image']) ?>" alt="<?= esc($post['title']) ?>" width="800" height="450" loading="lazy">
                                    </a>
                                <?php endif; ?>

                                <div class="post-content">
                                    <div class="post-meta-row">
                                        <span class="post-chip"><?= esc($post['category']) ?></span>
                                        <span><?= formatBlogDate($post['created_at']) ?></span>
                                    </div>

                                    <h3><a href="post.php?slug=<?= urlencode($post['slug']) ?>"><?= esc($post['title']) ?></a></h3>
                                    <p><?= esc($post['excerpt']) ?></p>

                                    <div class="post-footer-row">
                                        <span>By <?= esc($post['author']) ?></span>
                                        <span><?= esc((string) blogReadTime($post['content'])) ?> min read</span>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <div class="empty-state hidden" id="noResults">
                        <h3>No matching posts found</h3>
                        <p>Try a different keyword or switch back to all categories.</p>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>No blog posts yet</h3>
                        <p>Your blog page is ready. Add your first article file and it will appear here.</p>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="sidebar-column">
                <div class="sidebar-card">
                    <div class="sidebar-head">
                        <h3>Categories</h3>
                    </div>
                    <?php if ($categoryCounts): ?>
                        <div class="category-list">
                            <?php foreach ($categoryCounts as $category => $count): ?>
                                <button class="category-item quick-filter" type="button" data-category="<?= esc(strtolower($category)) ?>">
                                    <span><?= esc($category) ?></span>
                                    <strong><?= esc((string) $count) ?></strong>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="sidebar-empty">No categories available yet.</p>
                    <?php endif; ?>
                </div>

                <div class="sidebar-card">
                    <div class="sidebar-head">
                        <h3>Recent posts</h3>
                    </div>
                    <?php if ($recentPosts): ?>
                        <div class="recent-list">
                            <?php foreach ($recentPosts as $post): ?>
                                <a class="recent-item" href="post.php?slug=<?= urlencode($post['slug']) ?>">
                                    <span class="recent-title"><?= esc($post['title']) ?></span>
                                    <span class="recent-meta"><?= formatBlogDate($post['created_at']) ?> · <?= esc($post['category']) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="sidebar-empty">No recent posts yet.</p>
                    <?php endif; ?>
                </div>
            </aside>
        </section>
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