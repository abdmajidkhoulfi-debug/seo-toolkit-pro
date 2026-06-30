<?php
$tool = $tool ?? [];
$toolData = $toolData ?? [];
$relatedTools = $relatedTools ?? [];
$relatedPosts = $relatedPosts ?? [];
$steps = $toolData['steps'] ?? [];
$benefits = $toolData['benefits'] ?? [];
$faqs = $toolData['faqs'] ?? [];
?>
<div class="container" style="padding-top:48px;padding-bottom:96px;">
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" style="margin-bottom:24px;">
        <ol style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;list-style:none;padding:0;margin:0;">
            <li style="font-size:14px;color:var(--text-muted);"><a href="/" style="color:var(--text-secondary);">Home</a><span style="margin-left:8px;color:var(--text-muted);">/</span></li>
            <li style="font-size:14px;color:var(--text-muted);" aria-current="page"><?php echo \App\Helpers\SEO::esc($tool['name'] ?? ''); ?></li>
        </ol>
    </nav>

    <!-- Schema.org BreadcrumbList -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "<?php echo \App\Helpers\SEO::esc($config['app']['url'] ?? ''); ?>/"},
        {"@type": "ListItem", "position": 2, "name": "<?php echo \App\Helpers\SEO::esc($tool['name'] ?? ''); ?>", "item": "<?php echo \App\Helpers\SEO::esc($config['app']['url'] ?? ''); ?>/tools/<?php echo \App\Helpers\SEO::esc($tool['slug'] ?? ''); ?>"}
      ]
    }
    </script>

    <!-- Header -->
    <div style="max-width:800px;margin-bottom:48px;">
        <h1 style="font-size:2.5rem;font-weight:800;letter-spacing:-0.04em;margin:0 0 16px;"><?php echo \App\Helpers\SEO::esc($toolData['heading'] ?: $tool['name'] ?? ''); ?></h1>
        <p style="font-size:1.1rem;color:var(--text-secondary);line-height:1.7;margin:0;"><?php echo \App\Helpers\SEO::esc($toolData['intro'] ?? ''); ?></p>
    </div>

    <!-- How to Use -->
    <?php if (!empty($steps)): ?>
    <div style="background:var(--bg-secondary);border-radius:var(--radius-2xl);padding:48px;margin-bottom:48px;">
        <h2 style="font-size:1.5rem;margin:0 0 32px;">How to Use</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));gap:24px;">
            <?php foreach ($steps as $i => $step): ?>
            <div style="display:flex;gap:16px;">
                <div style="width:40px;height:40px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:16px;flex-shrink:0;"><?php echo $i + 1; ?></div>
                <div>
                    <h3 style="font-size:15px;margin:0 0 6px;"><?php echo \App\Helpers\SEO::esc($step['title']); ?></h3>
                    <p style="font-size:14px;color:var(--text-secondary);line-height:1.6;margin:0;"><?php echo \App\Helpers\SEO::esc($step['description']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- The Tool -->
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-2xl);padding:48px;margin-bottom:48px;">
        <h2 style="font-size:1.5rem;margin:0 0 24px;">Try It Now</h2>
        <?php
        $viewFile = __DIR__ . '/' . ($tool['slug'] ?? '') . '-ui.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo '<p style="color:var(--text-secondary);">Tool interface loading...</p>';
        }
        ?>
    </div>

    <!-- Benefits -->
    <?php if (!empty($benefits)): ?>
    <div style="margin-bottom:48px;">
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

    <!-- FAQ -->
    <?php if (!empty($faqs)): ?>
    <div style="max-width:800px;margin-bottom:48px;">
        <h2 style="font-size:1.5rem;margin:0 0 24px;">Frequently Asked Questions</h2>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <?php foreach ($faqs as $faq): ?>
            <details style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;">
                <summary style="padding:20px 24px;font-weight:600;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;list-style:none;">
                    <?php echo \App\Helpers\SEO::esc($faq['question']); ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;transition:transform 200ms ease;"><polyline points="6 9 12 15 18 9"/></svg>
                </summary>
                <div style="padding:0 24px 20px;font-size:14px;color:var(--text-secondary);line-height:1.7;">
                    <?php echo \App\Helpers\SEO::esc($faq['answer']); ?>
                </div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Related Tools -->
    <?php if (!empty($relatedTools)): ?>
    <div style="margin-bottom:48px;">
        <h2 style="font-size:1.5rem;margin:0 0 24px;">Related Tools</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(240px, 1fr));gap:16px;">
            <?php foreach ($relatedTools as $rt): ?>
            <a href="<?php echo \App\Helpers\SEO::esc($rt['url']); ?>" class="card" style="padding:20px;text-decoration:none;display:flex;align-items:center;gap:12px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                <span style="font-size:14px;font-weight:600;color:var(--text);"><?php echo \App\Helpers\SEO::esc($rt['name']); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Related Blog Posts -->
    <?php if (!empty($relatedPosts)): ?>
    <div style="margin-bottom:48px;">
        <h2 style="font-size:1.5rem;margin:0 0 24px;">Related Articles</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:20px;">
            <?php foreach ($relatedPosts as $rp): ?>
            <a href="/blog/<?php echo \App\Helpers\SEO::esc($rp['slug']); ?>" style="text-decoration:none;display:block;padding:20px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);transition:all 200ms ease;">
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:8px;"><?php echo \App\Helpers\SEO::esc($rp['category_name'] ?? 'General'); ?></div>
                <h3 style="font-size:15px;margin:0 0 8px;line-height:1.4;color:var(--text);"><?php echo \App\Helpers\SEO::esc($rp['title']); ?></h3>
                <p style="font-size:13px;color:var(--text-secondary);margin:0;line-height:1.5;"><?php echo \App\Helpers\SEO::esc($rp['excerpt'] ?: strip_tags(mb_substr($rp['content'], 0, 120))); ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- CTA -->
    <div style="background:linear-gradient(135deg, var(--primary), var(--secondary));border-radius:var(--radius-2xl);padding:48px;text-align:center;color:#fff;">
        <h2 style="font-size:1.75rem;font-weight:800;margin:0 0 12px;letter-spacing:-0.03em;">Ready to Improve Your SEO?</h2>
        <p style="font-size:1.05rem;opacity:0.9;max-width:450px;margin:0 auto 24px;">Try all our free SEO tools. No signup needed.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="/tools/seo-analyzer" class="btn btn-lg" style="background:#fff;color:var(--primary);">Try SEO Analyzer</a>
            <a href="/blog" class="btn btn-lg" style="background:rgba(255,255,255,0.2);color:#fff;border:1px solid rgba(255,255,255,0.3);">Read Our Blog</a>
        </div>
    </div>
</div>
