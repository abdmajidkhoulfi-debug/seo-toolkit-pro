<?php
/**
 * PFSRV SEO - Application Configuration
 */
return [
    'app' => [
        'name' => 'PFSRV SEO',
        'tagline' => 'Professional SEO Toolkit for Modern Websites',
        'description' => 'Free SEO tools, guides, and resources to help you rank higher on Google. Analyze, optimize, and grow your online presence with PFSRV SEO.',
        'url' => 'https://pfsrv.com',
        'logo' => '/assets/images/logo.svg',
        'locale' => 'en_US',
    ],

    'database' => [
        'path' => __DIR__ . '/../storage/database/app.sqlite',
        'dir' => __DIR__ . '/../storage/database',
    ],

    'admin' => [
        'session_key' => 'pfsrv_admin_logged_in',
        'session_expiry' => 86400 * 7, // 7 days
    ],

    'seo' => [
        'default_image' => '/assets/images/og-default.png',
        'twitter_handle' => '@pfsrvseo',
        'separator' => '|',
    ],

    'blog' => [
        'posts_per_page' => 12,
        'recent_posts_limit' => 5,
        'excerpt_length' => 160,
    ],

    'pagination' => [
        'per_page' => 20,
    ],

    'cache' => [
        'enabled' => true,
        'ttl' => 3600,
    ],

    'adsense' => [
        'enabled' => true,
        'publisher_id' => '',
    ],

    'newsletter' => [
        'enabled' => true,
    ],

    'tools' => [
        [
            'id' => 'meta-tag-generator',
            'slug' => 'meta-tag-generator',
            'name' => 'Meta Tag Generator',
            'short_name' => 'Meta Tags',
            'description' => 'Generate optimized meta tags for better SEO. Preview how your page will appear in search results.',
            'icon' => 'tag',
            'color' => '#6366f1',
        ],
        [
            'id' => 'keyword-density-checker',
            'slug' => 'keyword-density-checker',
            'name' => 'Keyword Density Checker',
            'short_name' => 'Keyword Density',
            'description' => 'Analyze keyword density and frequency in your content to optimize for search engines.',
            'icon' => 'bar-chart',
            'color' => '#8b5cf6',
        ],
        [
            'id' => 'schema-generator',
            'slug' => 'schema-generator',
            'name' => 'Schema Markup Generator',
            'short_name' => 'Schema Generator',
            'description' => 'Create JSON-LD structured data for rich snippets. Supports Article, FAQ, LocalBusiness, Product, and Organization schemas.',
            'icon' => 'code',
            'color' => '#06b6d4',
        ],
        [
            'id' => 'robots-txt-generator',
            'slug' => 'robots-txt-generator',
            'name' => 'Robots.txt Generator',
            'short_name' => 'Robots.txt',
            'description' => 'Generate and validate your robots.txt file to control search engine crawling behavior.',
            'icon' => 'file-text',
            'color' => '#10b981',
        ],
        [
            'id' => 'seo-analyzer',
            'slug' => 'seo-analyzer',
            'name' => 'SEO Analyzer',
            'short_name' => 'SEO Analyzer',
            'description' => 'Comprehensive on-page SEO analysis. Check titles, meta tags, headings, images, links, and structured data.',
            'icon' => 'search',
            'color' => '#f59e0b',
        ],
        [
            'id' => 'url-extractor',
            'slug' => 'url-extractor',
            'name' => 'URL Extractor',
            'short_name' => 'URL Extractor',
            'description' => 'Extract and analyze all links from any webpage. Filter by internal, external, and nofollow links.',
            'icon' => 'link',
            'color' => '#ef4444',
        ],
    ],
];
