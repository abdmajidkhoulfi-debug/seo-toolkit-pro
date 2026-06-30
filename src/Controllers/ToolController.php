<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Services\SEOService;
use App\Services\InternalLinkService;
use App\Models\Post;

class ToolController
{
    public function show(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';

        $appConfig = require __DIR__ . '/../../config/app.php';
        $tool = null;
        foreach ($appConfig['tools'] as $t) {
            if ($t['slug'] === $slug) {
                $tool = $t;
                break;
            }
        }

        if (!$tool) {
            View::notFound();
            return;
        }

        $viewFile = "tools/{$slug}";
        $viewPath = __DIR__ . "/../../views/{$viewFile}.php";

        if (!file_exists($viewPath)) {
            // Use generic tool template if specific one doesn't exist
            $viewFile = 'tools/generic';
        }

        $toolData = $this->getToolData($slug);
        $seo = SEOService::toolPage(
            $slug,
            $toolData['meta_title'] ?? '',
            $toolData['meta_description'] ?? ''
        );

        $relatedTools = InternalLinkService::getRelatedTools($slug, 4);
        $relatedPosts = InternalLinkService::getRelatedPostsForTool($slug, 3);

        View::share('seo', $seo);
        View::render($viewFile, [
            'tool' => $tool,
            'toolData' => $toolData,
            'relatedTools' => $relatedTools,
            'relatedPosts' => $relatedPosts,
            'layout' => 'main',
        ]);
    }

    private function getToolData(string $slug): array
    {
        $data = [
            'meta-tag-generator' => [
                'meta_title' => 'Free Meta Tag Generator - Create SEO-Optimized Meta Tags',
                'meta_description' => 'Generate optimized meta tags for better SEO. Preview how your page appears in search results. Free online meta tag generator with live SERP preview.',
                'heading' => 'Meta Tag Generator',
                'intro' => 'Meta tags are essential for SEO. They tell search engines what your page is about and how it should appear in search results. Our free Meta Tag Generator helps you create perfectly optimized meta tags in seconds.',
                'steps' => [
                    ['title' => 'Enter Your Details', 'description' => 'Fill in your page title, description, keywords, and other meta information.'],
                    ['title' => 'Preview in Real-Time', 'description' => 'See exactly how your page will look in Google search results with our live SERP preview.'],
                    ['title' => 'Copy & Implement', 'description' => 'Copy the generated meta tags and paste them into your website\'s <head> section.'],
                ],
                'benefits' => [
                    'Improve click-through rates with compelling meta descriptions',
                    'Optimize title tags for target keywords',
                    'Preview social sharing appearance with Open Graph tags',
                    'Ensure proper indexing with correct robots directives',
                    'Save time with instant HTML code generation',
                ],
                'faqs' => [
                    ['question' => 'What are meta tags?', 'answer' => 'Meta tags are HTML elements that provide metadata about your webpage. They include title tags, meta descriptions, and other information that helps search engines understand your content.'],
                    ['question' => 'How long should a meta title be?', 'answer' => 'Meta titles should be 50-60 characters to ensure they display fully in search results. Google typically displays the first 50-60 characters of a title tag.'],
                    ['question' => 'What is the ideal meta description length?', 'answer' => 'Meta descriptions should be 150-160 characters. This ensures your description displays properly in search results without being truncated.'],
                    ['question' => 'Do meta tags affect SEO rankings?', 'answer' => 'Yes, meta tags are a crucial on-page SEO factor. Title tags are a direct ranking signal, while well-written meta descriptions improve click-through rates.'],
                    ['question' => 'What are Open Graph tags?', 'answer' => 'Open Graph tags control how your content appears when shared on social media platforms like Facebook, LinkedIn, and Twitter. They ensure your shared links display correctly.'],
                ],
            ],
            'keyword-density-checker' => [
                'meta_title' => 'Free Keyword Density Checker - Analyze Your Content',
                'meta_description' => 'Analyze keyword density and frequency in your content. Free online tool to check over-optimization and improve your SEO content strategy.',
                'heading' => 'Keyword Density Checker',
                'intro' => 'Keyword density is a critical SEO metric. Our free Keyword Density Checker analyzes your content to ensure you\'re using keywords effectively without over-optimizing.',
                'steps' => [
                    ['title' => 'Enter Your Content', 'description' => 'Paste your text or enter a URL to analyze the content on any webpage.'],
                    ['title' => 'Choose Analysis Options', 'description' => 'Select n-gram size (1-3 words) and filter stop words for more accurate results.'],
                    ['title' => 'Review Results', 'description' => 'View keyword frequency, density percentages, and visual charts to optimize your content.'],
                ],
                'benefits' => [
                    'Avoid keyword stuffing penalties from Google',
                    'Identify your most used keywords and phrases',
                    'Optimize content for target keywords',
                    'Track keyword usage across multiple pages',
                    'Improve content relevance and topical authority',
                ],
                'faqs' => [
                    ['question' => 'What is keyword density?', 'answer' => 'Keyword density is the percentage of times a keyword or phrase appears in your content compared to the total word count. It helps measure how focused your content is on specific topics.'],
                    ['question' => 'What is the ideal keyword density?', 'answer' => 'There is no strict rule, but most SEO experts recommend 1-3% keyword density. Focus on natural language and readability rather than hitting a specific percentage.'],
                    ['question' => 'What are stop words?', 'answer' => 'Stop words are common words like "the", "a", "is", and "and" that are filtered out during keyword analysis because they appear too frequently and don\'t carry significant meaning.'],
                    ['question' => 'What is n-gram analysis?', 'answer' => 'N-gram analysis examines phrases of N words. For example, 2-gram analysis looks at two-word phrases like "keyword density", while 3-gram looks at three-word phrases.'],
                    ['question' => 'Can I analyze competitor content?', 'answer' => 'Yes! Simply enter any URL into the tool to analyze the keyword density of any publicly accessible webpage.'],
                ],
            ],
            'schema-generator' => [
                'meta_title' => 'Free Schema Markup Generator - Create JSON-LD Structured Data',
                'meta_description' => 'Create JSON-LD structured data for rich snippets. Generate Article, FAQ, LocalBusiness, Product, and Organization schema markup for free.',
                'heading' => 'Schema Markup Generator',
                'intro' => 'Schema markup helps search engines understand your content and display rich snippets in search results. Our free Schema Markup Generator creates valid JSON-LD structured data for any page type.',
                'steps' => [
                    ['title' => 'Select Schema Type', 'description' => 'Choose from Article, FAQ, LocalBusiness, Product, or Organization schema types.'],
                    ['title' => 'Fill in the Details', 'description' => 'Complete the form fields specific to your chosen schema type. Required fields are marked.'],
                    ['title' => 'Copy & Implement', 'description' => 'Copy the generated JSON-LD code and add it to your webpage\'s <head> or <body> section.'],
                ],
                'benefits' => [
                    'Earn rich snippets in search results',
                    'Improve click-through rates with enhanced listings',
                    'Help Google understand your content better',
                    'Support multiple schema types in one tool',
                    'Get validated JSON-LD code ready to implement',
                ],
                'faqs' => [
                    ['question' => 'What is schema markup?', 'answer' => 'Schema markup is structured data that helps search engines understand the content on your website. It uses a standard vocabulary (Schema.org) to describe different types of content.'],
                    ['question' => 'What is JSON-LD?', 'answer' => 'JSON-LD (JavaScript Object Notation for Linked Data) is Google\'s recommended format for structured data. It\'s a script-based format that\'s easy to implement and maintain.'],
                    ['question' => 'What are rich snippets?', 'answer' => 'Rich snippets are enhanced search results that display additional information like star ratings, prices, FAQs, and event details. They\'re generated from structured data markup.'],
                    ['question' => 'Does schema markup improve rankings?', 'answer' => 'While schema markup isn\'t a direct ranking factor, it helps you earn rich snippets and can significantly improve click-through rates, which indirectly benefits SEO.'],
                    ['question' => 'How do I test my schema markup?', 'answer' => 'You can use Google\'s Rich Results Test or Schema.org\'s validator to test and validate your structured data implementation.'],
                ],
            ],
            'robots-txt-generator' => [
                'meta_title' => 'Free Robots.txt Generator - Create & Validate Robots.txt Files',
                'meta_description' => 'Generate and validate your robots.txt file to control search engine crawling behavior. Free online robots.txt generator with live preview.',
                'heading' => 'Robots.txt Generator',
                'intro' => 'A properly configured robots.txt file is essential for controlling how search engines crawl your website. Our free Robots.txt Generator helps you create the perfect robots.txt file.',
                'steps' => [
                    ['title' => 'Add User-Agent Rules', 'description' => 'Specify which search engine crawlers to target and their access rules.'],
                    ['title' => 'Configure Paths', 'description' => 'Add allowed and disallowed paths to control crawl access for different sections.'],
                    ['title' => 'Generate & Download', 'description' => 'Generate your robots.txt file and download it or copy it directly to your website root.'],
                ],
                'benefits' => [
                    'Control search engine crawl budget effectively',
                    'Prevent duplicate content issues',
                    'Block sensitive areas from being indexed',
                    'Specify sitemap locations',
                    'Set crawl delay for server protection',
                ],
                'faqs' => [
                    ['question' => 'What is a robots.txt file?', 'answer' => 'Robots.txt is a text file placed in your website root that tells search engine crawlers which pages they can or cannot access and index.'],
                    ['question' => 'Where should robots.txt be placed?', 'answer' => 'Robots.txt must be placed in the root directory of your website (e.g., https://yoursite.com/robots.txt) to be recognized by search engines.'],
                    ['question' => 'Can robots.txt prevent indexing?', 'answer' => 'Robots.txt prevents crawling, but pages may still be indexed if linked from other sites. Use the noindex meta tag to prevent indexing entirely.'],
                    ['question' => 'What is crawl delay?', 'answer' => 'Crawl delay specifies the number of seconds a crawler should wait between requests. This helps prevent server overload from aggressive crawling.'],
                    ['question' => 'What user-agents should I use?', 'answer' => 'Use * for all crawlers, Googlebot for Google, Bingbot for Bing, and specific user-agents for other search engines or crawlers.'],
                ],
            ],
            'seo-analyzer' => [
                'meta_title' => 'Free SEO Analyzer - Comprehensive On-Page SEO Checker',
                'meta_description' => 'Analyze any webpage for SEO issues. Check titles, meta tags, headings, images, links, and structured data with our free SEO analyzer tool.',
                'heading' => 'SEO Analyzer',
                'intro' => 'Our comprehensive SEO Analyzer scans any webpage for critical on-page SEO factors. Get actionable insights to improve your search engine rankings.',
                'steps' => [
                    ['title' => 'Enter a URL', 'description' => 'Enter the URL of the page you want to analyze, including https://.'],
                    ['title' => 'Automatic Analysis', 'description' => 'Our tool scans the page and checks over 20 different SEO factors in seconds.'],
                    ['title' => 'Review & Optimize', 'description' => 'Review the detailed audit with pass/warn/fail indicators and follow recommendations to improve.'],
                ],
                'benefits' => [
                    'Comprehensive on-page SEO analysis in seconds',
                    'Check 20+ SEO factors including titles, headings, and images',
                    'Identify critical issues that hurt your rankings',
                    'Get actionable recommendations for improvement',
                    'Track SEO improvements over time',
                ],
                'faqs' => [
                    ['question' => 'What does the SEO analyzer check?', 'answer' => 'It checks title tags, meta descriptions, headings (H1-H6), image alt text, internal/external links, Open Graph tags, Twitter Cards, structured data, and more.'],
                    ['question' => 'How accurate is the analysis?', 'answer' => 'Our tool performs a comprehensive client-side analysis that checks all the key on-page SEO elements. It provides accurate and actionable recommendations.'],
                    ['question' => 'Can I analyze any website?', 'answer' => 'Yes, you can analyze any publicly accessible webpage by entering its full URL. The tool will scan and report on all available on-page SEO elements.'],
                    ['question' => 'What is the difference between pass/warn/fail?', 'answer' => 'Pass indicates the element is optimized, warn suggests improvement is recommended, and fail indicates a critical issue that needs immediate attention.'],
                    ['question' => 'How often should I run an SEO analysis?', 'answer' => 'We recommend running an SEO analysis whenever you publish new content or make significant changes to existing pages.'],
                ],
            ],
            'url-extractor' => [
                'meta_title' => 'Free URL Extractor - Extract & Analyze Links from Any Page',
                'meta_description' => 'Extract and analyze all links from any webpage. Filter by internal, external, and nofollow links. Free online URL extraction tool for SEO audits.',
                'heading' => 'URL Extractor',
                'intro' => 'Understanding your link profile is crucial for SEO. Our free URL Extractor extracts every link from any webpage and categorizes them by type for easy analysis.',
                'steps' => [
                    ['title' => 'Enter a URL', 'description' => 'Enter the URL of the page you want to analyze to extract all links.'],
                    ['title' => 'Automatic Extraction', 'description' => 'Our tool extracts all links with their anchor text, rel attributes, and target information.'],
                    ['title' => 'Filter & Analyze', 'description' => 'Filter links by type (all, internal, external, nofollow) and review the detailed analysis.'],
                ],
                'benefits' => [
                    'Complete link audit in seconds',
                    'Identify internal and external link distribution',
                    'Detect broken or nofollow links',
                    'Analyze anchor text distribution',
                    'Improve internal linking structure',
                ],
                'faqs' => [
                    ['question' => 'What is a URL extractor?', 'answer' => 'A URL extractor is a tool that scans a webpage and extracts all links found on that page, including their URLs, anchor text, and attributes.'],
                    ['question' => 'Why extract URLs from a page?', 'answer' => 'URL extraction helps with SEO audits, competitive analysis, finding broken links, and understanding how a page links internally and externally.'],
                    ['question' => 'What is the difference between internal and external links?', 'answer' => 'Internal links point to other pages on the same website, while external links point to pages on different domains. Both are important for SEO.'],
                    ['question' => 'What are nofollow links?', 'answer' => 'Nofollow links have a rel="nofollow" attribute that tells search engines not to pass link equity to the linked page. They\'re commonly used for sponsored content and user-generated content.'],
                    ['question' => 'How can I improve my internal linking?', 'answer' => 'Use descriptive anchor text, link to relevant content, avoid too many links per page, and ensure important pages receive more internal links.'],
                ],
            ],
        ];

        return $data[$slug] ?? [
            'meta_title' => '',
            'meta_description' => '',
            'heading' => '',
            'intro' => '',
            'steps' => [],
            'benefits' => [],
            'faqs' => [],
        ];
    }
}
