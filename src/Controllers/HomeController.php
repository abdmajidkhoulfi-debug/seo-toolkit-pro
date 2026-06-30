<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Models\Post;
use App\Models\Setting;
use App\Services\SEOService;
use App\Services\NewsletterService;
use App\Helpers\SEO;

class HomeController
{
    public function index(Request $request): void
    {
        $seo = SEOService::page([
            'title' => 'Free SEO Tools & Resources - Boost Your Search Rankings',
            'description' => 'Free professional SEO tools including Meta Tag Generator, Keyword Density Checker, Schema Markup Generator, and more. Analyze, optimize, and grow your online presence.',
            'url' => Setting::get('site_url', '') ?: '/',
        ]);

        $siteName = Setting::get('site_name', 'PFSRV SEO');
        $siteTagline = Setting::get('site_tagline', 'Professional SEO Toolkit for Modern Websites');

        $featuredPosts = Post::getFeatured(3);
        if (count($featuredPosts) < 3) {
            $featuredPosts = Post::getRecent(3);
        }

        $tools = require __DIR__ . '/../../config/app.php';
        $tools = $tools['tools'];

        $testimonials = [
            [
                'name' => 'Sarah Chen',
                'role' => 'Marketing Director, GrowthBox',
                'avatar' => 'SC',
                'content' => 'PFSRV SEO tools saved us hundreds of dollars. The meta tag generator and SEO analyzer are incredibly accurate and easy to use.',
                'rating' => 5,
            ],
            [
                'name' => 'Marcus Johnson',
                'role' => 'Freelance SEO Consultant',
                'avatar' => 'MJ',
                'content' => 'I use the keyword density checker daily for my clients. It\'s fast, precise, and the keyword suggestions help me optimize content better.',
                'rating' => 5,
            ],
            [
                'name' => 'Elena Rodriguez',
                'role' => 'Content Manager, TechFlow',
                'avatar' => 'ER',
                'content' => 'The schema markup generator is a game-changer. We\'ve seen a 40% increase in rich snippets since we started using it.',
                'rating' => 5,
            ],
        ];

        View::share('seo', $seo);
        View::render('home', [
            'title' => $siteName,
            'siteName' => $siteName,
            'siteTagline' => $siteTagline,
            'featuredPosts' => $featuredPosts,
            'tools' => $tools,
            'testimonials' => $testimonials,
            'layout' => 'main',
        ]);
    }

    public function subscribe(Request $request): void
    {
        $email = $request->post('email', '');
        $name = $request->post('name', '');
        $result = NewsletterService::subscribe($email, $name);

        if ($request->wantsJson() || $request->isAjax()) {
            View::json($result);
            return;
        }

        View::redirect('/?subscribed=' . ($result['success'] ? '1' : '0'));
    }

    public function about(Request $request): void
    {
        $seo = SEOService::page([
            'title' => 'About Us',
            'description' => 'Learn about PFSRV SEO - our mission to provide free, professional SEO tools and resources for website owners, marketers, and SEO professionals.',
            'url' => Setting::get('site_url', '') . '/about',
        ]);

        View::share('seo', $seo);
        View::render('pages/about', ['layout' => 'main']);
    }

    public function contact(Request $request): void
    {
        $seo = SEOService::page([
            'title' => 'Contact Us',
            'description' => 'Get in touch with the PFSRV SEO team. Have questions, suggestions, or need help with our SEO tools? We\'d love to hear from you.',
            'url' => Setting::get('site_url', '') . '/contact',
        ]);

        View::share('seo', $seo);
        View::render('pages/contact', ['layout' => 'main']);
    }

    public function privacy(Request $request): void
    {
        $seo = SEOService::page([
            'title' => 'Privacy Policy',
            'description' => 'Read our Privacy Policy to understand how PFSRV SEO collects, uses, and protects your personal information.',
            'url' => Setting::get('site_url', '') . '/privacy-policy',
            'robots' => 'index, follow',
        ]);

        View::share('seo', $seo);
        View::render('pages/privacy', ['layout' => 'main']);
    }

    public function terms(Request $request): void
    {
        $seo = SEOService::page([
            'title' => 'Terms & Conditions',
            'description' => 'Review the terms and conditions for using PFSRV SEO tools and services.',
            'url' => Setting::get('site_url', '') . '/terms',
            'robots' => 'index, follow',
        ]);

        View::share('seo', $seo);
        View::render('pages/terms', ['layout' => 'main']);
    }

    public function disclaimer(Request $request): void
    {
        $seo = SEOService::page([
            'title' => 'Disclaimer',
            'description' => 'Read our disclaimer regarding the use of PFSRV SEO tools and the information provided on our website.',
            'url' => Setting::get('site_url', '') . '/disclaimer',
            'robots' => 'index, follow',
        ]);

        View::share('seo', $seo);
        View::render('pages/disclaimer', ['layout' => 'main']);
    }
}
