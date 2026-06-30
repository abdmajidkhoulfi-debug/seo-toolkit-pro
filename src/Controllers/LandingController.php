<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Services\SEOService;
use App\Helpers\SEO;

class LandingController
{
    public function show(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';

        $landingPages = $this->getLandingPages();
        $page = null;

        foreach ($landingPages as $lp) {
            if ($lp['slug'] === $slug) {
                $page = $lp;
                break;
            }
        }

        if (!$page) {
            View::notFound();
            return;
        }

        $similarPages = array_values(array_filter($landingPages, fn($p) => $p['slug'] !== $slug));
        shuffle($similarPages);
        $similarPages = array_slice($similarPages, 0, 4);

        $relatedTools = [];
        foreach ($page['related_tools'] ?? [] as $toolSlug) {
            $appConfig = require __DIR__ . '/../../config/app.php';
            foreach ($appConfig['tools'] as $t) {
                if ($t['slug'] === $toolSlug) {
                    $relatedTools[] = $t;
                    break;
                }
            }
        }

        $seo = SEOService::page([
            'title' => $page['meta_title'],
            'description' => $page['meta_description'],
            'url' => (require __DIR__ . '/../../config/app.php')['app']['url'] . '/' . $slug,
        ]);

        View::share('seo', $seo);
        View::render('landing', [
            'page' => $page,
            'similarPages' => $similarPages,
            'relatedTools' => $relatedTools,
            'layout' => 'main',
        ]);
    }

    private function getLandingPages(): array
    {
        return [
            [
                'slug' => 'best-seo-tools-for-shopify',
                'meta_title' => 'Best SEO Tools for Shopify - Boost Your Store Rankings',
                'meta_description' => 'Discover the best free SEO tools for your Shopify store. Analyze, optimize, and grow your Shopify search rankings with our professional toolkit.',
                'h1' => 'Best SEO Tools for Shopify Stores',
                'intro' => 'Running a successful Shopify store requires more than great products — you need visibility. Our free SEO tools help Shopify merchants optimize their stores for search engines, drive organic traffic, and increase sales.',
                'content_sections' => [
                    [
                        'heading' => 'Why Shopify Stores Need SEO Tools',
                        'content' => 'Shopify is a powerful ecommerce platform, but out-of-the-box SEO is rarely enough to compete. With thousands of stores selling similar products, you need every advantage to rank higher in search results.<br><br>Our SEO tools help you optimize product pages, meta tags, and content structure to give your Shopify store the competitive edge it needs.',
                    ],
                    [
                        'heading' => 'Essential SEO Tools for Shopify Merchants',
                        'content' => 'From meta tag optimization to keyword research, our free tools cover every aspect of Shopify SEO. Use our Meta Tag Generator to optimize product titles and descriptions, the Keyword Density Checker to ensure natural keyword usage, and the Schema Markup Generator to enable rich snippets for your products.',
                    ],
                    [
                        'heading' => 'Shopify SEO Best Practices',
                        'content' => 'Start with meta tags — optimize your title tags with primary keywords and keep them under 60 characters. Write unique product descriptions that include relevant keywords naturally. Use our SEO Analyzer to identify technical issues that might be holding your store back.',
                    ],
                ],
                'benefits' => [
                    'Optimize product meta tags for higher click-through rates',
                    'Analyze keyword usage in product descriptions',
                    'Generate schema markup for product rich snippets',
                    'Check on-page SEO factors with comprehensive analysis',
                    'Create clean robots.txt to guide crawlers effectively',
                ],
                'related_tools' => ['meta-tag-generator', 'keyword-density-checker', 'schema-generator', 'seo-analyzer'],
                'faqs' => [
                    ['question' => 'Does Shopify have built-in SEO features?', 'answer' => 'Shopify provides basic SEO features like customizable title tags and meta descriptions. However, our free tools provide advanced optimization capabilities to help you outrank competitors.'],
                    ['question' => 'How can I optimize product pages for SEO?', 'answer' => 'Use unique product titles with keywords, write detailed descriptions, add alt text to images, and use our Schema Markup Generator to enable product rich snippets.'],
                    ['question' => 'What is the best meta description length for Shopify?', 'answer' => 'Keep meta descriptions between 150-160 characters. Include primary keywords and a compelling call-to-action to improve click-through rates from search results.'],
                ],
            ],
            [
                'slug' => 'best-seo-tools-for-lawyers',
                'meta_title' => 'Best SEO Tools for Lawyers - Legal SEO Toolkit',
                'meta_description' => 'Free SEO tools designed for law firms and attorneys. Improve your legal practice visibility with meta tag optimization, schema markup, and SEO analysis.',
                'h1' => 'Best SEO Tools for Lawyers & Law Firms',
                'intro' => 'In today\'s competitive legal market, potential clients find attorneys through search engines. Our free SEO tools help law firms optimize their online presence, attract more qualified leads, and grow their practice.',
                'content_sections' => [
                    [
                        'heading' => 'Legal SEO: Why It Matters',
                        'content' => 'When someone needs a lawyer, they search Google. If your firm doesn\'t appear on the first page, you\'re losing potential clients to competitors. Legal SEO requires specialized optimization for practice areas, locations, and client intent.<br><br>Our tools help law firms optimize their websites for local SEO, practice area pages, and attorney bio pages.',
                    ],
                    [
                        'heading' => 'Key SEO Tools for Law Firms',
                        'content' => 'Use our LocalBusiness Schema Generator to help Google display your firm\'s hours, phone number, and reviews in search results. Our Meta Tag Generator creates optimized titles for practice area pages. The SEO Analyzer checks your site for technical issues.',
                    ],
                    [
                        'heading' => 'Local SEO Strategies for Attorneys',
                        'content' => 'Optimize your Google Business Profile, ensure consistent NAP (Name, Address, Phone) across all directories, create location-specific practice area pages, and earn reviews from satisfied clients. Use our tools to optimize each page for maximum visibility.',
                    ],
                ],
                'benefits' => [
                    'Generate LocalBusiness schema for Google rich results',
                    'Optimize practice area pages with targeted meta tags',
                    'Analyze competitor SEO strategies',
                    'Create SEO-optimized attorney bio pages',
                    'Improve local search visibility',
                ],
                'related_tools' => ['schema-generator', 'meta-tag-generator', 'seo-analyzer', 'keyword-density-checker'],
                'faqs' => [
                    ['question' => 'How long does legal SEO take?', 'answer' => 'Legal SEO typically takes 3-6 months to see significant results. Start with on-page optimization using our free tools, then build quality backlinks and local citations.'],
                    ['question' => 'What schema markup do law firms need?', 'answer' => 'Law firms should use LocalBusiness schema with practice area specialization, Attorney schema, and FAQ schema for common legal questions. Our Schema Generator supports all these types.'],
                    ['question' => 'How can I rank for "lawyer near me" searches?', 'answer' => 'Optimize for local SEO by claiming your Google Business Profile, getting reviews, creating location-specific pages, and using local keywords in your meta tags.'],
                ],
            ],
            [
                'slug' => 'best-seo-tools-for-dentists',
                'meta_title' => 'Best SEO Tools for Dentists - Dental Practice SEO',
                'meta_description' => 'Free SEO tools for dental practices. Attract more patients with optimized meta tags, local schema markup, and comprehensive SEO analysis for your dental website.',
                'h1' => 'Best SEO Tools for Dentists',
                'intro' => 'Patients search for dentists online every day. Our free SEO tools help dental practices appear in local search results, attract new patients, and grow their practice with effective search engine optimization.',
                'content_sections' => [
                    [
                        'heading' => 'Why Dental Practices Need SEO',
                        'content' => 'The first place patients look for a dentist is Google. Without proper SEO, your practice gets buried under competitors. Dental SEO helps you appear in local pack results, attract emergency patients, and build trust through online visibility.',
                    ],
                    [
                        'heading' => 'Dental SEO Tools & Strategies',
                        'content' => 'Use our LocalBusiness Schema Generator to display your services, hours, and patient reviews in search results. Our Meta Tag Generator creates optimized titles for each service page. Check your site health with the SEO Analyzer.',
                    ],
                    [
                        'heading' => 'Attract More Dental Patients Online',
                        'content' => 'Optimize for local keywords like "dentist in [city]" and "[service] dentist". Create separate pages for each service (teeth whitening, crowns, implants). Use our tools to ensure every page is fully optimized.',
                    ],
                ],
                'benefits' => [
                    'LocalBusiness schema for better local visibility',
                    'Optimized meta tags for dental service pages',
                    'Comprehensive on-page SEO analysis',
                    'Keyword density analysis for natural optimization',
                    'Clean robots.txt for proper crawling',
                ],
                'related_tools' => ['schema-generator', 'meta-tag-generator', 'seo-analyzer', 'keyword-density-checker'],
                'faqs' => [
                    ['question' => 'What is the most important SEO factor for dentists?', 'answer' => 'Local SEO is critical for dentists. Optimize your Google Business Profile, get patient reviews, and use local keywords. Our LocalBusiness schema helps Google display your practice details.'],
                    ['question' => 'How do I rank for emergency dentist searches?', 'answer' => 'Create emergency-specific landing pages, use relevant keywords like "emergency dentist [city]", and optimize for "near me" searches. Keep your Google Business Profile hours updated.'],
                ],
            ],
            [
                'slug' => 'best-seo-tools-for-restaurants',
                'meta_title' => 'Best SEO Tools for Restaurants - Restaurant SEO Guide',
                'meta_description' => 'Free SEO tools for restaurants and food businesses. Optimize your menu pages, attract more diners, and rank higher in local search results.',
                'h1' => 'Best SEO Tools for Restaurants',
                'intro' => 'When people search for places to eat, they turn to Google. Our free SEO tools help restaurants appear in local searches, attract more customers, and build a strong online presence.',
                'content_sections' => [
                    [
                        'heading' => 'Restaurant SEO: A Recipe for Success',
                        'content' => 'Restaurant SEO is about being found when hungry customers search. From "restaurants near me" to "best [cuisine] in [city]", our tools help you optimize every aspect of your online presence.',
                    ],
                    [
                        'heading' => 'Essential SEO for Your Restaurant Website',
                        'content' => 'Optimize menu pages with descriptive titles and meta descriptions. Use our Schema Generator for Menu and LocalBusiness markup. Check your page speed and mobile optimization with our tools.',
                    ],
                ],
                'benefits' => [
                    'LocalBusiness schema for restaurant details',
                    'Optimized meta tags for menu and location pages',
                    'Comprehensive SEO analysis for food websites',
                    'Keyword optimization for cuisine and location terms',
                ],
                'related_tools' => ['schema-generator', 'meta-tag-generator', 'seo-analyzer'],
                'faqs' => [
                    ['question' => 'How do I rank for "restaurants near me"?', 'answer' => 'Optimize your Google Business Profile, encourage reviews, use LocalBusiness schema, and include city and neighborhood names in your content.'],
                ],
            ],
            [
                'slug' => 'best-seo-tools-for-real-estate',
                'meta_title' => 'Best SEO Tools for Real Estate Agents',
                'meta_description' => 'Free SEO tools for real estate agents and agencies. Optimize property listings, attract more home buyers, and dominate local real estate search results.',
                'h1' => 'Best SEO Tools for Real Estate',
                'intro' => 'Real estate is a local business, and local SEO is everything. Our free tools help real estate agents and agencies optimize their listings, attract buyers and sellers, and dominate local search results.',
                'content_sections' => [
                    [
                        'heading' => 'Real Estate SEO: Why It Matters',
                        'content' => 'Home buyers and sellers start their search online. Without strong SEO, your listings get lost in a sea of competitors. Our tools help you optimize property pages, agent profiles, and neighborhood guides.',
                    ],
                ],
                'benefits' => [
                    'Optimize property listing meta tags',
                    'Generate schema for real estate listings',
                    'Analyze competitor SEO strategies',
                    'Improve local search visibility',
                    'Create optimized agent profile pages',
                ],
                'related_tools' => ['meta-tag-generator', 'schema-generator', 'seo-analyzer'],
                'faqs' => [
                    ['question' => 'How can real estate agents improve local SEO?', 'answer' => 'Create neighborhood-specific pages, optimize Google Business Profile, get client reviews, and use local keywords. Our tools help with all aspects of local SEO optimization.'],
                ],
            ],
            [
                'slug' => 'best-seo-tools-for-ecommerce',
                'meta_title' => 'Best SEO Tools for Ecommerce - Online Store SEO',
                'meta_description' => 'Free SEO tools for ecommerce websites. Optimize product pages, improve category SEO, and drive more organic traffic to your online store.',
                'h1' => 'Best SEO Tools for Ecommerce Websites',
                'intro' => 'Ecommerce SEO is the key to driving organic traffic and sales. Our free tools help online store owners optimize product pages, categories, and content for better search rankings.',
                'content_sections' => [
                    [
                        'heading' => 'Why Ecommerce Stores Need Specialized SEO',
                        'content' => 'Ecommerce websites face unique SEO challenges: duplicate content from product variations, thin content on category pages, and intense competition. Our tools address each of these challenges.',
                    ],
                ],
                'benefits' => [
                    'Product schema for rich search results',
                    'Category page meta tag optimization',
                    'Keyword density analysis for product descriptions',
                    'SEO audit for technical ecommerce issues',
                    'Robots.txt optimization for proper crawling',
                ],
                'related_tools' => ['meta-tag-generator', 'keyword-density-checker', 'schema-generator', 'seo-analyzer', 'robots-txt-generator'],
                'faqs' => [
                    ['question' => 'What is the most important SEO factor for ecommerce?', 'answer' => 'Product page optimization is critical. Unique titles, detailed descriptions, customer reviews, and product schema markup all contribute to better rankings.'],
                ],
            ],
            [
                'slug' => 'best-seo-tools-for-small-business',
                'meta_title' => 'Best SEO Tools for Small Business Owners',
                'meta_description' => 'Free SEO tools for small businesses. Compete with larger companies using professional SEO analysis, meta tag optimization, and schema markup tools.',
                'h1' => 'Best SEO Tools for Small Businesses',
                'intro' => 'Small businesses need every advantage to compete online. Our free SEO tools help level the playing field, giving small business owners access to professional SEO capabilities without the enterprise price tag.',
                'content_sections' => [
                    [
                        'heading' => 'Small Business SEO: Compete and Win',
                        'content' => 'You don\'t need a massive budget to rank well in search. With the right tools and strategy, small businesses can outperform larger competitors through targeted local SEO, quality content, and technical optimization.',
                    ],
                ],
                'benefits' => [
                    'Professional SEO tools at zero cost',
                    'Local SEO optimization for neighborhood visibility',
                    'Easy-to-use tools for non-technical users',
                    'Comprehensive analysis and actionable recommendations',
                    'Schema markup to stand out in search results',
                ],
                'related_tools' => ['meta-tag-generator', 'seo-analyzer', 'schema-generator', 'keyword-density-checker'],
                'faqs' => [
                    ['question' => 'Can small businesses compete with big brands in SEO?', 'answer' => 'Yes! Small businesses often win in local SEO and niche markets. Focus on local keywords, earn genuine reviews, create quality content, and use our free tools to optimize every page.'],
                ],
            ],
            // Programmatic tool-specific landing pages
            [
                'slug' => 'keyword-density-checker-for-html',
                'meta_title' => 'Keyword Density Checker for HTML - Analyze Web Page Keywords',
                'meta_description' => 'Free keyword density checker for HTML pages. Paste any HTML code and analyze keyword frequency, density, and n-gram patterns instantly.',
                'h1' => 'Keyword Density Checker for HTML Pages',
                'intro' => 'Need to analyze the keyword density of raw HTML? Our tool strips away the markup and analyzes just the visible content, giving you accurate keyword frequency and density metrics.',
                'content_sections' => [],
                'benefits' => ['Accurate HTML content analysis', 'N-gram support', 'Stop word filtering', 'Visual charts and data tables'],
                'related_tools' => ['keyword-density-checker', 'seo-analyzer', 'url-extractor'],
                'faqs' => [
                    ['question' => 'Does the tool analyze HTML tags?', 'answer' => 'No, the tool automatically strips HTML tags and analyzes only the visible text content for accurate keyword density measurement.'],
                ],
            ],
            [
                'slug' => 'keyword-density-checker-for-wordpress',
                'meta_title' => 'Keyword Density Checker for WordPress - Optimize Your Content',
                'meta_description' => 'Free keyword density checker for WordPress content. Analyze your WordPress posts and pages to optimize keyword usage and improve SEO rankings.',
                'h1' => 'Keyword Density Checker for WordPress',
                'intro' => 'WordPress powers millions of websites, and content optimization is key to SEO success. Our tool helps WordPress users analyze their content\'s keyword density to ensure natural, effective optimization.',
                'content_sections' => [],
                'benefits' => ['WordPress content analysis', 'Avoid keyword stuffing', 'Improve content relevance', 'Better search rankings'],
                'related_tools' => ['keyword-density-checker', 'meta-tag-generator', 'seo-analyzer'],
                'faqs' => [],
            ],
            [
                'slug' => 'meta-tag-generator-for-wordpress',
                'meta_title' => 'Meta Tag Generator for WordPress - SEO Plugin Alternative',
                'meta_description' => 'Free meta tag generator for WordPress. Create optimized meta titles and descriptions for your WordPress posts, pages, and products without installing plugins.',
                'h1' => 'Meta Tag Generator for WordPress',
                'intro' => 'While WordPress SEO plugins are popular, sometimes you need a quick way to generate optimized meta tags. Our free tool helps you create the perfect meta tags for any WordPress page.',
                'content_sections' => [],
                'benefits' => ['No plugin installation needed', 'Live SERP preview', 'Character count optimization', 'Open Graph and Twitter Card support'],
                'related_tools' => ['meta-tag-generator', 'seo-analyzer', 'schema-generator'],
                'faqs' => [
                    ['question' => 'Do I still need a WordPress SEO plugin?', 'answer' => 'While our tool generates optimized meta tags, a WordPress SEO plugin helps manage them at scale. Use our tool for individual page optimization and testing.'],
                ],
            ],
            [
                'slug' => 'meta-tag-generator-for-shopify',
                'meta_title' => 'Meta Tag Generator for Shopify - Product SEO Tool',
                'meta_description' => 'Free meta tag generator for Shopify stores. Create SEO-optimized product titles and meta descriptions to improve your Shopify store search rankings.',
                'h1' => 'Meta Tag Generator for Shopify',
                'intro' => 'Shopify product pages need careful meta tag optimization to stand out. Our tool helps you craft the perfect title tags and meta descriptions for your products.',
                'content_sections' => [],
                'benefits' => ['Shopify product optimization', 'Live search preview', 'Character count tracking', 'OG tags for social sharing'],
                'related_tools' => ['meta-tag-generator', 'schema-generator', 'seo-analyzer'],
                'faqs' => [],
            ],
        ];
    }
}
