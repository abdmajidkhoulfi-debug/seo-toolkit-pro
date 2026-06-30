<?php
$siteName = $siteName ?? 'PFSRV SEO';
$siteTagline = $siteTagline ?? 'Professional SEO Toolkit for Modern Websites';
$tools = $tools ?? [];
$featuredPosts = $featuredPosts ?? [];
$testimonials = $testimonials ?? [];
$config = require __DIR__ . '/../config/app.php';
$appUrl = $config['app']['url'] ?? '';
?>

<!-- HERO SECTION -->
<section style="padding:120px 0 80px;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-200px;right:-200px;width:600px;height:600px;border-radius:50%;background:radial-gradient(circle, color-mix(in srgb, var(--primary) 20%, transparent) 0%, transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-100px;left:-100px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle, color-mix(in srgb, var(--secondary) 15%, transparent) 0%, transparent 70%);pointer-events:none;"></div>
    <div class="container" style="position:relative;z-index:1;">
        <div style="max-width:800px;margin:0 auto;text-align:center;">
            <div class="animate-fade-in-up">
                <span class="badge" style="margin-bottom:24px;">&#9733; Free SEO Tools</span>
            </div>
            <h1 class="animate-fade-in-up stagger-1" style="font-size:3.5rem;font-weight:800;line-height:1.1;margin:0 0 20px;letter-spacing:-0.04em;">
                Rank Higher.<br>
                <span class="gradient-text">Grow Faster.</span>
            </h1>
            <p class="animate-fade-in-up stagger-2" style="font-size:1.2rem;color:var(--text-secondary);line-height:1.7;max-width:600px;margin:0 auto 32px;">
                Professional SEO tools to analyze, optimize, and grow your online presence.
                Free, fast, and built for modern websites.
            </p>
            <div class="animate-fade-in-up stagger-3" style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                <a href="/tools/seo-analyzer" class="btn btn-primary btn-lg">Analyze Your Site</a>
                <a href="/tools/meta-tag-generator" class="btn btn-secondary btn-lg">Generate Meta Tags</a>
            </div>
            <div class="animate-fade-in-up stagger-4" style="display:flex;align-items:center;justify-content:center;gap:32px;margin-top:48px;flex-wrap:wrap;">
                <div style="text-align:center;">
                    <div style="font-size:28px;font-weight:800;color:var(--primary);">6</div>
                    <div style="font-size:13px;color:var(--text-muted);">Free Tools</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:28px;font-weight:800;color:var(--primary);">100%</div>
                    <div style="font-size:13px;color:var(--text-muted);">Free to Use</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:28px;font-weight:800;color:var(--primary);">No Signup</div>
                    <div style="font-size:13px;color:var(--text-muted);">Required</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TOOLS SECTION -->
<section class="section" style="background:var(--bg-secondary);">
    <div class="container">
        <div class="section-head">
            <h2>All SEO Tools</h2>
            <p>Everything you need to optimize your website for search engines. Free, fast, and accurate.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(340px, 1fr));gap:24px;">
            <?php foreach ($tools as $tool): ?>
            <a href="/tools/<?php echo $tool['slug']; ?>" class="card" style="display:flex;gap:20px;align-items:flex-start;text-decoration:none;padding:28px;">
                <div style="width:52px;height:52px;border-radius:14px;display:grid;place-items:center;background:color-mix(in srgb, <?php echo $tool['color']; ?> 15%, transparent);color:<?php echo $tool['color']; ?>;flex-shrink:0;font-size:22px;">
                    <?php
                    $icons = [
                        'tag' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>',
                        'bar-chart' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
                        'code' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
                        'file-text' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>',
                        'search' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
                        'link' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>',
                    ];
                    echo $icons[$tool['icon']] ?? '';
                    ?>
                </div>
                <div style="flex:1;min-width:0;">
                    <h3 style="font-size:1.1rem;margin:0 0 6px;color:var(--text);"><?php echo \App\Helpers\SEO::esc($tool['name']); ?></h3>
                    <p style="font-size:14px;color:var(--text-secondary);line-height:1.6;margin:0;"><?php echo \App\Helpers\SEO::esc($tool['description']); ?></p>
                </div>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" style="flex-shrink:0;margin-top:4px;"><path d="M9 18l6-6-6-6"/></svg>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <h2>Why Choose <?php echo \App\Helpers\SEO::esc($siteName); ?></h2>
            <p>Thousands of marketers and website owners trust our tools for accurate, reliable SEO analysis.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:24px;">
            <?php
            $features = [
                [
                    'icon' => '⚡',
                    'title' => 'Lightning Fast',
                    'desc' => 'Tools that work in milliseconds. No waiting, no delays. Get results instantly.',
                ],
                [
                    'icon' => '🎯',
                    'title' => 'Highly Accurate',
                    'desc' => 'Professional-grade analysis you can trust. Used by SEO experts worldwide.',
                ],
                [
                    'icon' => '🔒',
                    'title' => '100% Private',
                    'desc' => 'Your data stays on your device. We never store or share your content.',
                ],
                [
                    'icon' => '🆓',
                    'title' => 'Completely Free',
                    'desc' => 'No hidden charges, no premium tiers. All tools are free forever.',
                ],
                [
                    'icon' => '📱',
                    'title' => 'Mobile Friendly',
                    'desc' => 'Fully responsive tools that work perfectly on any device.',
                ],
                [
                    'icon' => '🌐',
                    'title' => 'No Signup Needed',
                    'desc' => 'Start using any tool immediately. No account creation required.',
                ],
            ];
            foreach ($features as $f): ?>
            <div class="card" style="text-align:center;padding:32px 24px;">
                <div style="font-size:36px;margin-bottom:16px;"><?php echo $f['icon']; ?></div>
                <h3 style="font-size:1.1rem;margin:0 0 8px;"><?php echo \App\Helpers\SEO::esc($f['title']); ?></h3>
                <p style="font-size:14px;color:var(--text-secondary);line-height:1.6;margin:0;"><?php echo \App\Helpers\SEO::esc($f['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<?php if (!empty($testimonials)): ?>
<section class="section" style="background:var(--bg-secondary);">
    <div class="container">
        <div class="section-head">
            <h2>What Our Users Say</h2>
            <p>Join thousands of satisfied users who trust our SEO tools.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:24px;">
            <?php foreach ($testimonials as $t): ?>
            <div class="card" style="padding:28px;">
                <div style="display:flex;gap:2px;margin-bottom:16px;">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="<?php echo $i < $t['rating'] ? '#f59e0b' : 'var(--border)'; ?>" stroke="<?php echo $i < $t['rating'] ? '#f59e0b' : 'var(--border)'; ?>">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <?php endfor; ?>
                </div>
                <p style="font-size:14px;color:var(--text-secondary);line-height:1.7;margin:0 0 20px;">"<?php echo \App\Helpers\SEO::esc($t['content']); ?>"</p>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:50%;background:var(--primary-soft);color:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;"><?php echo $t['avatar']; ?></div>
                    <div>
                        <div style="font-weight:600;font-size:14px;"><?php echo \App\Helpers\SEO::esc($t['name']); ?></div>
                        <div style="font-size:13px;color:var(--text-muted);"><?php echo \App\Helpers\SEO::esc($t['role']); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- FEATURED BLOG POSTS -->
<?php if (!empty($featuredPosts)): ?>
<section class="section">
    <div class="container">
        <div class="section-head" style="display:flex;align-items:flex-end;justify-content:space-between;max-width:none;text-align:left;">
            <div>
                <h2>Latest from the Blog</h2>
                <p>Fresh SEO guides, tips, and tutorials to help you rank better.</p>
            </div>
            <a href="/blog" class="btn btn-secondary btn-sm" style="flex-shrink:0;">View All Posts →</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:24px;">
            <?php foreach ($featuredPosts as $post): ?>
            <a href="/blog/<?php echo \App\Helpers\SEO::esc($post['slug']); ?>" class="card" style="padding:0;overflow:hidden;text-decoration:none;display:flex;flex-direction:column;">
                <?php if (!empty($post['featured_image'])): ?>
                <img src="<?php echo \App\Helpers\SEO::esc($post['featured_image']); ?>" alt="<?php echo \App\Helpers\SEO::esc($post['alt_text'] ?: $post['title']); ?>" style="width:100%;height:200px;object-fit:cover;" loading="lazy">
                <?php else: ?>
                <div style="width:100%;height:200px;background:linear-gradient(135deg, var(--primary-soft), color-mix(in srgb, var(--primary) 20%, transparent));display:grid;place-items:center;font-size:48px;color:var(--primary);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                <?php endif; ?>
                <div style="padding:20px;flex:1;display:flex;flex-direction:column;">
                    <div style="display:flex;gap:8px;margin-bottom:10px;flex-wrap:wrap;">
                        <?php if (!empty($post['category_name'])): ?>
                        <span class="badge"><?php echo \App\Helpers\SEO::esc($post['category_name']); ?></span>
                        <?php endif; ?>
                        <span style="font-size:13px;color:var(--text-muted);"><?php echo date('M j, Y', strtotime($post['published_at'] ?? $post['created_at'])); ?></span>
                    </div>
                    <h3 style="font-size:1.15rem;letter-spacing:-0.03em;margin:0 0 8px;line-height:1.3;"><?php echo \App\Helpers\SEO::esc($post['title']); ?></h3>
                    <p style="font-size:14px;color:var(--text-secondary);line-height:1.6;margin:0 0 auto;"><?php echo \App\Helpers\SEO::esc($post['excerpt'] ?: strip_tags(mb_substr($post['content'], 0, 150))); ?></p>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px;">
                        <span style="font-size:13px;color:var(--text-muted);">By <?php echo \App\Helpers\SEO::esc($post['author']); ?></span>
                        <span style="font-size:14px;font-weight:600;color:var(--primary);">Read →</span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- FAQ SECTION -->
<section class="section" style="background:var(--bg-secondary);">
    <div class="container" style="max-width:800px;">
        <div class="section-head">
            <h2>Frequently Asked Questions</h2>
            <p>Everything you need to know about our SEO tools.</p>
        </div>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <?php
            $faqs = [
                ['q' => 'Are your SEO tools really free?', 'a' => 'Yes! All our SEO tools are completely free to use with no hidden charges, premium tiers, or usage limits. We believe everyone should have access to professional SEO tools.'],
                ['q' => 'Do I need to create an account?', 'a' => 'No. You can use any of our tools immediately without signing up or creating an account. Your privacy matters to us.'],
                ['q' => 'How accurate are your tools?', 'a' => 'Our tools use industry-standard algorithms and are regularly tested against leading SEO platforms. They provide professional-grade accuracy suitable for serious SEO work.'],
                ['q' => 'Can I use these tools for client work?', 'a' => 'Absolutely! You can use our tools for your own websites, client projects, or any commercial work. No attribution required.'],
                ['q' => 'Do you store my data?', 'a' => 'No. All analysis happens in your browser. We never store, share, or sell your data. Your content stays private.'],
                ['q' => 'How is this different from other SEO tools?', 'a' => 'Unlike other tools that charge hundreds of dollars per month, we provide professional-grade SEO capabilities completely free. No signup, no limits, no ads cluttering your experience.'],
            ];
            foreach ($faqs as $faq): ?>
            <details style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;">
                <summary style="padding:20px 24px;font-weight:600;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;list-style:none;">
                    <?php echo \App\Helpers\SEO::esc($faq['q']); ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;transition:transform 200ms ease;"><polyline points="6 9 12 15 18 9"/></svg>
                </summary>
                <div style="padding:0 24px 20px;font-size:14px;color:var(--text-secondary);line-height:1.7;">
                    <?php echo \App\Helpers\SEO::esc($faq['a']); ?>
                </div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="section">
    <div class="container">
        <div style="background:linear-gradient(135deg, var(--primary), var(--secondary));border-radius:var(--radius-2xl);padding:64px 48px;text-align:center;color:#fff;">
            <h2 style="font-size:2.2rem;font-weight:800;margin:0 0 16px;letter-spacing:-0.03em;">Start Optimizing Your Site Today</h2>
            <p style="font-size:1.1rem;opacity:0.9;max-width:500px;margin:0 auto 32px;line-height:1.6;">
                No signup required. No credit card. Just professional SEO tools at your fingertips.
            </p>
            <a href="/tools/seo-analyzer" class="btn btn-lg" style="background:#fff;color:var(--primary);box-shadow:0 4px 20px rgba(0,0,0,0.2);">Try SEO Analyzer →</a>
        </div>
    </div>
</section>
