<?php
namespace App\Helpers;

class SEO
{
    private static array $defaults = [];

    public static function init(array $config): void
    {
        self::$defaults = $config;
    }

    public static function renderMeta(array $page = []): string
    {
        $title = $page['title'] ?? self::$defaults['name'] ?? 'PFSRV SEO';
        $description = $page['description'] ?? self::$defaults['description'] ?? '';
        $url = $page['url'] ?? self::$defaults['url'] ?? '';
        $image = $page['image'] ?? self::$defaults['default_image'] ?? '';
        $separator = self::$defaults['separator'] ?? '|';
        $siteName = self::$defaults['name'] ?? 'PFSRV SEO';
        $twitterHandle = self::$defaults['twitter_handle'] ?? '';

        if (!str_contains($title, $separator)) {
            $fullTitle = trim("{$title} {$separator} {$siteName}");
        } else {
            $fullTitle = $title;
        }

        $html = '';

        // Title
        $html .= "<title>" . self::esc($fullTitle) . "</title>\n";
        $html .= '<meta name="description" content="' . self::esc($description) . "\">\n";

        // Canonical
        if ($url) {
            $html .= '<link rel="canonical" href="' . self::esc($url) . "\">\n";
        }

        // Open Graph
        $html .= '<meta property="og:type" content="' . self::esc($page['og_type'] ?? 'website') . "\">\n";
        $html .= '<meta property="og:title" content="' . self::esc($fullTitle) . "\">\n";
        $html .= '<meta property="og:description" content="' . self::esc($description) . "\">\n";
        $html .= '<meta property="og:url" content="' . self::esc($url) . "\">\n";
        $html .= '<meta property="og:site_name" content="' . self::esc($siteName) . "\">\n";
        $html .= '<meta property="og:locale" content="' . self::esc($page['locale'] ?? 'en_US') . "\">\n";

        if ($image) {
            $html .= '<meta property="og:image" content="' . self::esc($image) . "\">\n";
            $html .= '<meta property="og:image:width" content="1200\">\n';
            $html .= '<meta property="og:image:height" content="630\">\n';
        }

        // Twitter Card
        $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
        $html .= '<meta name="twitter:title" content="' . self::esc($fullTitle) . "\">\n";
        $html .= '<meta name="twitter:description" content="' . self::esc($description) . "\">\n";

        if ($image) {
            $html .= '<meta name="twitter:image" content="' . self::esc($image) . "\">\n";
        }

        if ($twitterHandle) {
            $html .= '<meta name="twitter:site" content="' . self::esc($twitterHandle) . "\">\n";
            $html .= '<meta name="twitter:creator" content="' . self::esc($twitterHandle) . "\">\n";
        }

        // Robots
        $robots = $page['robots'] ?? 'index, follow, max-image-preview:large';
        $html .= '<meta name="robots" content="' . self::esc($robots) . "\">\n";

        return $html;
    }

    public static function renderSchema(string $type, array $data = []): string
    {
        $schema = [];

        switch ($type) {
            case 'Organization':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => $data['name'] ?? self::$defaults['name'] ?? 'PFSRV SEO',
                    'description' => $data['description'] ?? self::$defaults['description'] ?? '',
                    'url' => $data['url'] ?? self::$defaults['url'] ?? '',
                    'logo' => $data['logo'] ?? self::$defaults['logo'] ?? '',
                    'sameAs' => $data['sameAs'] ?? [],
                ];
                break;

            case 'SoftwareApplication':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'SoftwareApplication',
                    'name' => $data['name'] ?? '',
                    'description' => $data['description'] ?? '',
                    'url' => $data['url'] ?? '',
                    'applicationCategory' => 'UtilitiesApplication',
                    'operatingSystem' => 'All',
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => '0',
                        'priceCurrency' => 'USD',
                        'availability' => 'https://schema.org/InStock',
                    ],
                ];
                break;

            case 'FAQ':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => [],
                ];
                foreach ($data['questions'] ?? [] as $faq) {
                    $schema['mainEntity'][] = [
                        '@type' => 'Question',
                        'name' => $faq['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq['answer'],
                        ],
                    ];
                }
                break;

            case 'Article':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'headline' => $data['headline'] ?? '',
                    'description' => $data['description'] ?? '',
                    'image' => $data['image'] ?? '',
                    'author' => [
                        '@type' => 'Person',
                        'name' => $data['author'] ?? 'Admin',
                    ],
                    'datePublished' => $data['datePublished'] ?? '',
                    'dateModified' => $data['dateModified'] ?? '',
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => self::$defaults['name'] ?? 'PFSRV SEO',
                    ],
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => $data['url'] ?? '',
                    ],
                ];
                break;

            case 'BreadcrumbList':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [],
                ];
                foreach ($data['items'] ?? [] as $i => $item) {
                    $schema['itemListElement'][] = [
                        '@type' => 'ListItem',
                        'position' => $i + 1,
                        'name' => $item['name'],
                        'item' => $item['url'],
                    ];
                }
                break;

            case 'WebPage':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => $data['name'] ?? '',
                    'description' => $data['description'] ?? '',
                    'url' => $data['url'] ?? '',
                    'isPartOf' => [
                        '@type' => 'WebSite',
                        '@id' => (self::$defaults['url'] ?? '') . '/#website',
                    ],
                ];
                break;

            case 'ItemList':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'ItemList',
                    'itemListElement' => [],
                ];
                foreach ($data['items'] ?? [] as $i => $item) {
                    $schema['itemListElement'][] = [
                        '@type' => 'ListItem',
                        'position' => $i + 1,
                        'name' => $item['name'] ?? '',
                        'url' => $item['url'] ?? '',
                    ];
                }
                break;
        }

        if (!empty($schema)) {
            return '<script type="application/ld+json">' . "\n"
                . json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                . "\n</script>\n";
        }

        return '';
    }

    public static function renderBreadcrumb(array $items): string
    {
        if (empty($items)) {
            return '';
        }

        $html = '<nav aria-label="Breadcrumb" class="breadcrumb">';
        $html .= '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';

        foreach ($items as $i => $item) {
            $isLast = $i === count($items) - 1;
            $html .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';

            if ($isLast) {
                $html .= '<span itemprop="name">' . self::esc($item['name']) . '</span>';
            } else {
                $html .= '<a href="' . self::esc($item['url']) . '" itemprop="item"><span itemprop="name">'
                    . self::esc($item['name']) . '</span></a>';
                $html .= '<span class="sep" aria-hidden="true">/</span>';
            }

            $html .= '<meta itemprop="position" content="' . ($i + 1) . '">';
            $html .= '</li>';
        }

        $html .= '</ol></nav>';

        return $html;
    }

    public static function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = str_replace(
            ['à','á','â','ã','ä','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ù','ú','û','ü','ý','ÿ'],
            ['a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','u','u','u','u','y','y'],
            $text
        );
        $text = preg_replace('/[^a-z0-9-]/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        return trim($text, '-');
    }

    public static function readingTime(string $content, int $wpm = 200): int
    {
        $words = str_word_count(strip_tags($content));
        return max(1, (int) ceil($words / $wpm));
    }

    public static function truncate(string $text, int $length = 160): string
    {
        $text = strip_tags($text);
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return rtrim(mb_substr($text, 0, $length)) . '...';
    }

    public static function esc(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
