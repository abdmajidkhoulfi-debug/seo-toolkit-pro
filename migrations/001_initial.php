<?php
/**
 * Initial database migration
 * Run: php migrations/migrate.php
 */

return function (PDO $pdo) {
    $pdo->exec('
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            slug TEXT NOT NULL UNIQUE,
            description TEXT DEFAULT "",
            parent_id INTEGER DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
        )
    ');

    $pdo->exec('
        CREATE TABLE IF NOT EXISTS tags (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            slug TEXT NOT NULL UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');

    $pdo->exec('
        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            slug TEXT NOT NULL UNIQUE,
            content TEXT NOT NULL,
            excerpt TEXT DEFAULT "",
            featured_image TEXT DEFAULT "",
            alt_text TEXT DEFAULT "",
            category_id INTEGER DEFAULT NULL,
            author TEXT DEFAULT "Admin",
            status TEXT DEFAULT "published" CHECK(status IN ("published","draft","scheduled")),
            published_at DATETIME DEFAULT NULL,
            scheduled_at DATETIME DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            meta_title TEXT DEFAULT "",
            meta_description TEXT DEFAULT "",
            og_image TEXT DEFAULT "",
            canonical_url TEXT DEFAULT "",
            allow_comments INTEGER DEFAULT 1,
            featured INTEGER DEFAULT 0,
            view_count INTEGER DEFAULT 0,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
        )
    ');

    $pdo->exec('
        CREATE INDEX idx_posts_slug ON posts(slug)
    ');
    $pdo->exec('
        CREATE INDEX idx_posts_status ON posts(status)
    ');
    $pdo->exec('
        CREATE INDEX idx_posts_published_at ON posts(published_at)
    ');
    $pdo->exec('
        CREATE INDEX idx_posts_category ON posts(category_id)
    ');
    $pdo->exec('
        CREATE INDEX idx_posts_featured ON posts(featured)
    ');

    $pdo->exec('
        CREATE TABLE IF NOT EXISTS post_tags (
            post_id INTEGER NOT NULL,
            tag_id INTEGER NOT NULL,
            PRIMARY KEY (post_id, tag_id),
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
        )
    ');

    $pdo->exec('
        CREATE TABLE IF NOT EXISTS comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER NOT NULL,
            parent_id INTEGER DEFAULT NULL,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            website TEXT DEFAULT "",
            content TEXT NOT NULL,
            status TEXT DEFAULT "pending" CHECK(status IN ("approved","pending","spam")),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE
        )
    ');

    $pdo->exec('
        CREATE INDEX idx_comments_post ON comments(post_id)
    ');
    $pdo->exec('
        CREATE INDEX idx_comments_status ON comments(status)
    ');

    $pdo->exec('
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            name TEXT DEFAULT "Admin",
            role TEXT DEFAULT "admin" CHECK(role IN ("admin","editor","author")),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            last_login DATETIME DEFAULT NULL
        )
    ');

    $pdo->exec('
        CREATE TABLE IF NOT EXISTS subscribers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT NOT NULL UNIQUE,
            name TEXT DEFAULT "",
            subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            active INTEGER DEFAULT 1,
            token TEXT DEFAULT ""
        )
    ');

    $pdo->exec('
        CREATE TABLE IF NOT EXISTS settings (
            key TEXT PRIMARY KEY,
            value TEXT NOT NULL,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');

    $pdo->exec('
        CREATE TABLE IF NOT EXISTS pages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            slug TEXT NOT NULL UNIQUE,
            content TEXT NOT NULL,
            meta_title TEXT DEFAULT "",
            meta_description TEXT DEFAULT "",
            status TEXT DEFAULT "published" CHECK(status IN ("published","draft")),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');

    $pdo->exec('
        CREATE TABLE IF NOT EXISTS media (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            filename TEXT NOT NULL,
            original_name TEXT NOT NULL,
            mime_type TEXT NOT NULL,
            size INTEGER NOT NULL,
            alt_text TEXT DEFAULT "",
            uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');

    // Insert default categories
    $categories = [
        ['SEO Basics', 'seo-basics', 'Learn the fundamentals of search engine optimization'],
        ['Technical SEO', 'technical-seo', 'Advanced technical SEO guides and tutorials'],
        ['Keyword Research', 'keyword-research', 'Keyword research strategies and tools'],
        ['Backlinks', 'backlinks', 'Link building strategies and techniques'],
        ['AI SEO', 'ai-seo', 'Artificial intelligence in SEO and content creation'],
        ['Content Marketing', 'content-marketing', 'Content marketing strategies for SEO'],
        ['Google Updates', 'google-updates', 'Latest Google algorithm updates and changes'],
        ['Local SEO', 'local-seo', 'Local SEO strategies for small businesses'],
        ['WordPress SEO', 'wordpress-seo', 'WordPress SEO optimization guides'],
        ['Ecommerce SEO', 'ecommerce-seo', 'Ecommerce SEO strategies for online stores'],
    ];

    $stmt = $pdo->prepare('INSERT OR IGNORE INTO categories (name, slug, description) VALUES (?, ?, ?)');
    foreach ($categories as $cat) {
        $stmt->execute($cat);
    }

    // Insert default settings
    $settings = [
        ['site_name', 'PFSRV SEO'],
        ['site_tagline', 'Professional SEO Toolkit for Modern Websites'],
        ['site_description', 'Free SEO tools, guides, and resources to help you rank higher on Google.'],
        ['primary_color', '#6366f1'],
        ['secondary_color', '#06b6d4'],
        ['header_code', ''],
        ['footer_code', ''],
        ['adsense_publisher_id', ''],
        ['newsletter_enabled', '1'],
        ['comments_enabled', '1'],
    ];

    $stmt = $pdo->prepare('INSERT OR IGNORE INTO settings (key, value) VALUES (?, ?)');
    foreach ($settings as $s) {
        $stmt->execute($s);
    }
};
