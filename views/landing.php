<?php
$page = $page ?? [];
$similarPages = $similarPages ?? [];
$relatedTools = $relatedTools ?? [];
$sections = $page['content_sections'] ?? [];
$benefits = $page['benefits'] ?? [];
$faqs = $page['faqs'] ?? [];
$config = require __DIR__ . '/../config/app.php';
$appUrl = $config['app']['url'] ?? '';
?>

<div class="container" style="padding-top:48px;padding-bottom:96px;max-width:900px;">
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" style="margin-bottom:24px;">
        <ol style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;list-style:none;padding:0;margin:0;">
            <li style="font-size:14px;color:var(--text-muted);"><a href="/" style="color:var(--text-secondary);">Home</a><span style="margin-left:8px;color:var(--text-muted);">/</span></li>
            <li style="font-size:14px;color:var(--text-muted);" aria-current="page"><?php echo \App\Helpers\SEO::esc($page['h1'] ?? ''); ?></li>
        </ol>
    </nav>

    <h1 style="font-size:2.8rem;font-weight:800;letter-spacing:-0.04em;margin:0 0 20px;"><?php echo \App\Helpers\SEO::esc($page['h1'] ?? ''); ?></h1>
    <p style="font-size:1.1rem;color:var(--text-secondary);line-height:1.7;margin-bottom:40px;"><?php echo \App\Helpers\SEO::esc($page['intro'] ?? ''); ?></p>

    <!-- Content Sections -->
    <?php foreach ($sections as $section): ?>
    <div class="seo-content" style="margin-bottom:40px;">
        <h2><?php echo \App\Helpers\SEO::esc($section['heading']); ?></h2>
        <p><?php echo $section['content']; ?></p>
    </div>
    <?php endforeach; ?>

    <!-- Benefits -->
    <?php if (!empty($benefits)): ?>
    <div style="margin-bottom:40px;">
        <h2 style="font-size:1.5rem;margin:0 0 24px;">Benefits</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:16px;">
            <?php foreach ($benefits as $b): ?>
            <div style="display:flex;align-items:flex-start;gap:12px;padding:16px;background:var(--bg-secondary);border-radius:var(--radius-lg);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2.5" style="flex-shrink:0;margin-top:2px;"><polyline points="20 6 9 17 4 12"/></svg>
                <span style="font-size:14px;color:var(--text-secondary);line-height:1.5;"><?php echo \App\Helpers\SEO::esc($b); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- CTA -->
    <div style="background:linear-gradient(135deg, var(--primary), var(--secondary));border-radius:var(--radius-2xl);padding:48px;text-align:center;color:#fff;margin-bottom:48px;">
        <h2 style="font-size:1.5rem;margin:0 0 12px;">Try Our Free SEO Tools</h2>
        <p style="opacity:0.9;max-width:400px;margin:0 auto 24px;">Start optimizing your website today with our professional SEO toolkit.</p>
        <a href="/tools/seo-analyzer" class="btn btn-lg" style="background:#fff;color:var(--primary);">Analyze Your Site Now →</a>
    </div>

    <!-- Related Tools -->
    <?php if (!empty($relatedTools)): ?>
    <div style="margin-bottom:40px;">
        <h2 style="font-size:1.5rem;margin:0 0 24px;">Recommended Tools</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(240px, 1fr));gap:16px;">
            <?php foreach ($relatedTools as $rt): ?>
            <a href="/tools/<?php echo $rt['slug']; ?>" class="card" style="padding:20px;text-decoration:none;display:flex;align-items:center;gap:12px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                <span style="font-size:14px;font-weight:600;"><?php echo \App\Helpers\SEO::esc($rt['name']); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- FAQ -->
    <?php if (!empty($faqs)): ?>
    <div style="margin-bottom:40px;">
        <h2 style="font-size:1.5rem;margin:0 0 24px;">Frequently Asked Questions</h2>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <?php foreach ($faqs as $faq): ?>
            <details style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;">
                <summary style="padding:20px 24px;font-weight:600;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;list-style:none;">
                    <?php echo \App\Helpers\SEO::esc($faq['question']); ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </summary>
                <div style="padding:0 24px 20px;font-size:14px;color:var(--text-secondary);line-height:1.7;"><?php echo \App\Helpers\SEO::esc($faq['answer']); ?></div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Similar Pages -->
    <?php if (!empty($similarPages)): ?>
    <div>
        <h2 style="font-size:1.5rem;margin:0 0 24px;">Related Resources</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(240px, 1fr));gap:16px;">
            <?php foreach ($similarPages as $sp): ?>
            <a href="/<?php echo $sp['slug']; ?>" style="padding:16px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);text-decoration:none;transition:all 200ms ease;">
                <div style="font-size:14px;font-weight:600;color:var(--text);line-height:1.4;"><?php echo \App\Helpers\SEO::esc($sp['h1'] ?? $sp['meta_title']); ?></div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
