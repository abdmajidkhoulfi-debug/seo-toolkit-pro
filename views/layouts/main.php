<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    // Render SEO meta from the $seo array passed by controllers
    if (isset($seo) && is_array($seo)) {
        echo \App\Helpers\SEO::renderMeta($seo);
    }
    ?>

    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/app.css">
    <?php if (isset($extraCss)) echo $extraCss; ?>

    <!-- Schema.org Organization -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "<?php echo \App\Helpers\SEO::esc($siteName ?? 'PFSRV SEO'); ?>",
      "url": "<?php echo $config['app']['url'] ?? ''; ?>",
      "description": "<?php echo \App\Helpers\SEO::esc($config['app']['description'] ?? ''); ?>",
      "logo": "<?php echo ($config['app']['url'] ?? '') . ($config['app']['logo'] ?? ''); ?>",
      "sameAs": []
    }
    </script>

    <!-- WebSite schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "<?php echo \App\Helpers\SEO::esc($siteName ?? 'PFSRV SEO'); ?>",
      "url": "<?php echo $config['app']['url'] ?? ''; ?>",
      "potentialAction": {
        "@type": "SearchAction",
        "target": {
          "@type": "EntryPoint",
          "urlTemplate": "<?php echo ($config['app']['url'] ?? '') . '/blog?search={search_term_string}'; ?>"
        },
        "query-input": "required name=search_term_string"
      }
    }
    </script>

    <!-- Header Code -->
    <?php if (!empty($headerCode)): ?>
    <?php echo $headerCode; ?>
    <?php endif; ?>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to content</a>

    <!-- HEADER -->
    <header class="site-header" id="siteHeader" style="position:sticky;top:0;z-index:200;backdrop-filter:blur(14px);background:color-mix(in srgb, var(--bg) 84%, transparent);border-bottom:1px solid color-mix(in srgb, var(--text) 10%, transparent);transition:transform 200ms ease;">
        <div class="container" style="display:flex;align-items:center;justify-content:space-between;gap:16px;min-height:var(--header-h);">
            <a href="/" class="brand" aria-label="<?php echo \App\Helpers\SEO::esc($siteName ?? 'PFSRV SEO'); ?> home" style="display:inline-flex;align-items:center;gap:12px;font-weight:700;flex-shrink:0;">
                <span style="width:40px;height:40px;border-radius:12px;display:grid;place-items:center;color:#fff;background:linear-gradient(135deg,var(--primary),color-mix(in srgb, var(--primary) 60%, #fff));box-shadow:var(--shadow-sm);font-size:18px;font-weight:800;">P</span>
                <span style="font-family:var(--font-display);font-size:1.1rem;letter-spacing:-0.03em;"><?php echo \App\Helpers\SEO::esc($siteName ?? 'PFSRV SEO'); ?></span>
            </a>

            <nav class="desktop-nav" aria-label="Primary navigation" style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                <a href="/" class="nav-link" style="padding:8px 14px;border-radius:8px;font-size:14px;font-weight:600;color:var(--text-muted);transition:all 200ms ease;">Home</a>
                <a href="/tools" class="nav-link" style="padding:8px 14px;border-radius:8px;font-size:14px;font-weight:600;color:var(--text-muted);transition:all 200ms ease;">Tools</a>
                <a href="/blog" class="nav-link" style="padding:8px 14px;border-radius:8px;font-size:14px;font-weight:600;color:var(--text-muted);transition:all 200ms ease;">Blog</a>
                <a href="/about" class="nav-link" style="padding:8px 14px;border-radius:8px;font-size:14px;font-weight:600;color:var(--text-muted);transition:all 200ms ease;">About</a>
                <a href="/contact" class="nav-link" style="padding:8px 14px;border-radius:8px;font-size:14px;font-weight:600;color:var(--text-muted);transition:all 200ms ease;">Contact</a>
            </nav>

            <div style="display:flex;align-items:center;gap:8px;">
                <button class="icon-btn" id="themeToggle" type="button" aria-label="Toggle theme" style="width:40px;height:40px;border-radius:10px;border:none;background:transparent;color:var(--text-secondary);cursor:pointer;display:grid;place-items:center;transition:all 200ms ease;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3A7 7 0 0 0 21 12.79Z"></path>
                    </svg>
                </button>

                <button class="menu-btn" id="menuToggle" type="button" aria-label="Open mobile menu" aria-expanded="false" style="display:none;width:40px;height:40px;border-radius:10px;border:none;background:transparent;color:var(--text-secondary);cursor:pointer;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M3 6H21M3 12H21M3 18H21" stroke-linecap="round"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Show mobile menu button on small screens -->
    <style>
        @media (max-width: 900px) {
            .desktop-nav { display: none !important; }
            .menu-btn { display: flex !important; }
        }
    </style>

    <!-- MOBILE NAV -->
    <aside class="mobile-nav" id="mobileNav" aria-hidden="true" style="position:fixed;top:0;left:0;right:0;bottom:0;z-index:300;pointer-events:none;opacity:0;transition:opacity 300ms ease;">
        <div id="mobileBackdrop" style="position:absolute;inset:0;background:rgba(0,0,0,0.5);"></div>
        <div class="mobile-nav-panel" style="position:absolute;top:0;left:0;bottom:0;width:300px;max-width:85vw;background:var(--surface);padding:24px;transform:translateX(-100%);transition:transform 300ms ease;overflow-y:auto;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:32px;">
                <strong style="font-size:18px;">Navigation</strong>
                <button id="menuClose" type="button" style="width:36px;height:36px;border-radius:8px;border:none;background:var(--bg-secondary);color:var(--text-secondary);cursor:pointer;font-size:18px;">✕</button>
            </div>
            <nav style="display:flex;flex-direction:column;gap:4px;">
                <a href="/" class="nav-link" style="display:block;padding:12px 16px;border-radius:10px;font-size:15px;font-weight:600;color:var(--text);transition:all 200ms ease;">Home</a>
                <a href="/tools/meta-tag-generator" class="nav-link" style="display:block;padding:12px 16px;border-radius:10px;font-size:15px;font-weight:600;color:var(--text);transition:all 200ms ease;">Meta Tag Generator</a>
                <a href="/tools/keyword-density-checker" class="nav-link" style="display:block;padding:12px 16px;border-radius:10px;font-size:15px;font-weight:600;color:var(--text);transition:all 200ms ease;">Keyword Density Checker</a>
                <a href="/tools/schema-generator" class="nav-link" style="display:block;padding:12px 16px;border-radius:10px;font-size:15px;font-weight:600;color:var(--text);transition:all 200ms ease;">Schema Markup Generator</a>
                <a href="/tools/robots-txt-generator" class="nav-link" style="display:block;padding:12px 16px;border-radius:10px;font-size:15px;font-weight:600;color:var(--text);transition:all 200ms ease;">Robots.txt Generator</a>
                <a href="/tools/seo-analyzer" class="nav-link" style="display:block;padding:12px 16px;border-radius:10px;font-size:15px;font-weight:600;color:var(--text);transition:all 200ms ease;">SEO Analyzer</a>
                <a href="/tools/url-extractor" class="nav-link" style="display:block;padding:12px 16px;border-radius:10px;font-size:15px;font-weight:600;color:var(--text);transition:all 200ms ease;">URL Extractor</a>
                <hr style="border:none;border-top:1px solid var(--border);margin:12px 0;">
                <a href="/blog" class="nav-link" style="display:block;padding:12px 16px;border-radius:10px;font-size:15px;font-weight:600;color:var(--text);transition:all 200ms ease;">Blog</a>
                <a href="/about" class="nav-link" style="display:block;padding:12px 16px;border-radius:10px;font-size:15px;font-weight:600;color:var(--text);transition:all 200ms ease;">About</a>
                <a href="/contact" class="nav-link" style="display:block;padding:12px 16px;border-radius:10px;font-size:15px;font-weight:600;color:var(--text);transition:all 200ms ease;">Contact</a>
                <a href="/privacy-policy" class="nav-link" style="display:block;padding:12px 16px;border-radius:10px;font-size:15px;font-weight:600;color:var(--text);transition:all 200ms ease;">Privacy Policy</a>
                <a href="/terms" class="nav-link" style="display:block;padding:12px 16px;border-radius:10px;font-size:15px;font-weight:600;color:var(--text);transition:all 200ms ease;">Terms</a>
                <a href="/disclaimer" class="nav-link" style="display:block;padding:12px 16px;border-radius:10px;font-size:15px;font-weight:600;color:var(--text);transition:all 200ms ease;">Disclaimer</a>
            </nav>
        </div>
    </aside>

    <style>
        .mobile-nav.open { pointer-events: all; opacity: 1; }
        .mobile-nav.open .mobile-nav-panel { transform: translateX(0); }
        .site-header.hide { transform: translateY(-100%); }
        .nav-link:hover { background: var(--bg-secondary); color: var(--text) !important; }
        .nav-link.active { background: var(--primary-soft); color: var(--primary) !important; }
    </style>

    <!-- MAIN CONTENT -->
    <main id="main-content">
        <?php echo $content ?? ''; ?>
    </main>

    <!-- FOOTER -->
    <footer style="border-top:1px solid var(--border);background:var(--bg-secondary);">
        <div class="container" style="padding:64px 24px 32px;">
            <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:48px;margin-bottom:48px;">
                <!-- Brand -->
                <div>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                        <span style="width:36px;height:36px;border-radius:10px;display:grid;place-items:center;color:#fff;background:linear-gradient(135deg,var(--primary),color-mix(in srgb, var(--primary) 60%, #fff));font-weight:800;">P</span>
                        <span style="font-family:var(--font-display);font-size:1.1rem;font-weight:700;letter-spacing:-0.03em;"><?php echo \App\Helpers\SEO::esc($siteName ?? 'PFSRV SEO'); ?></span>
                    </div>
                    <p style="color:var(--text-secondary);font-size:14px;line-height:1.7;max-width:320px;">
                        Professional SEO tools and resources to help you rank higher on Google. Analyze, optimize, and grow your online presence.
                    </p>
                </div>

                <!-- Tools -->
                <div>
                    <h4 style="font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);margin:0 0 16px;">Tools</h4>
                    <nav style="display:flex;flex-direction:column;gap:10px;">
                        <a href="/tools/meta-tag-generator" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">Meta Tag Generator</a>
                        <a href="/tools/keyword-density-checker" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">Keyword Density</a>
                        <a href="/tools/schema-generator" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">Schema Markup</a>
                        <a href="/tools/robots-txt-generator" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">Robots.txt</a>
                        <a href="/tools/seo-analyzer" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">SEO Analyzer</a>
                        <a href="/tools/url-extractor" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">URL Extractor</a>
                    </nav>
                </div>

                <!-- Resources -->
                <div>
                    <h4 style="font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);margin:0 0 16px;">Resources</h4>
                    <nav style="display:flex;flex-direction:column;gap:10px;">
                        <a href="/blog" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">Blog</a>
                        <a href="/blog/category/seo-basics" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">SEO Basics</a>
                        <a href="/blog/category/technical-seo" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">Technical SEO</a>
                        <a href="/blog/category/keyword-research" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">Keyword Research</a>
                        <a href="/blog/category/ai-seo" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">AI SEO</a>
                    </nav>
                </div>

                <!-- Company -->
                <div>
                    <h4 style="font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);margin:0 0 16px;">Company</h4>
                    <nav style="display:flex;flex-direction:column;gap:10px;">
                        <a href="/about" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">About</a>
                        <a href="/contact" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">Contact</a>
                        <a href="/privacy-policy" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">Privacy Policy</a>
                        <a href="/terms" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">Terms</a>
                        <a href="/disclaimer" style="font-size:14px;color:var(--text-secondary);transition:color 200ms ease;">Disclaimer</a>
                    </nav>
                </div>
            </div>

            <!-- Newsletter -->
            <div style="max-width:500px;margin:0 auto 48px;text-align:center;">
                <h3 style="font-size:18px;margin-bottom:8px;">Stay Updated</h3>
                <p style="font-size:14px;color:var(--text-secondary);margin-bottom:16px;">Get the latest SEO tips and tools delivered to your inbox.</p>
                <form class="newsletter-form" style="display:flex;gap:8px;max-width:400px;margin:0 auto;">
                    <input type="email" name="email" placeholder="your@email.com" required style="flex:1;padding:12px 16px;font-size:14px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);font-family:inherit;">
                    <button type="submit" class="btn btn-primary btn-sm">Subscribe</button>
                </form>
                <p class="newsletter-message hidden" style="font-size:13px;margin-top:8px;"></p>
            </div>

            <!-- Bottom bar -->
            <div style="border-top:1px solid var(--border);padding-top:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                <p style="font-size:13px;color:var(--text-muted);margin:0;">
                    &copy; <?php echo date('Y'); ?> <?php echo \App\Helpers\SEO::esc($siteName ?? 'PFSRV SEO'); ?>. All rights reserved.
                </p>
                <nav style="display:flex;gap:16px;">
                    <a href="/sitemap.xml" style="font-size:13px;color:var(--text-muted);transition:color 200ms ease;">Sitemap</a>
                    <a href="/feed.xml" style="font-size:13px;color:var(--text-muted);transition:color 200ms ease;">RSS Feed</a>
                </nav>
            </div>
        </div>
    </footer>

    <!-- Footer Code -->
    <?php if (!empty($footerCode)): ?>
    <?php echo $footerCode; ?>
    <?php endif; ?>

    <!-- Scripts -->
    <script src="/assets/js/app.js"></script>
    <?php if (isset($extraJs)) echo $extraJs; ?>
</body>
</html>
