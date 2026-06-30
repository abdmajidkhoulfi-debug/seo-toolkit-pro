<?php
$tools = $tools ?? [];
$config = require __DIR__ . '/../../config/app.php';
$appUrl = $config['app']['url'] ?? '';
?>
<div class="container" style="padding-top:48px;padding-bottom:96px;">
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" style="margin-bottom:24px;">
        <ol style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;list-style:none;padding:0;margin:0;">
            <li style="font-size:14px;color:var(--text-muted);"><a href="/" style="color:var(--text-secondary);">Home</a><span style="margin-left:8px;color:var(--text-muted);">/</span></li>
            <li style="font-size:14px;color:var(--text-muted);" aria-current="page">SEO Tools</li>
        </ol>
    </nav>

    <!-- Header -->
    <div style="max-width:800px;margin-bottom:48px;">
        <div style="display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border-radius:999px;font-size:13px;font-weight:600;background:var(--primary-soft);color:var(--primary);margin-bottom:16px;">🔧 All Free Tools</div>
        <h1 style="font-size:3rem;font-weight:800;letter-spacing:-0.04em;margin:0 0 16px;">SEO Tools</h1>
        <p style="font-size:1.15rem;color:var(--text-secondary);line-height:1.7;margin:0;">
            Professional SEO tools to analyze, optimize, and improve your website's search rankings. 
            All tools are <strong style="color:var(--text);">100% free</strong> — no signup required.
        </p>
    </div>

    <!-- Tool Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(360px, 1fr));gap:24px;margin-bottom:64px;">
        <?php foreach ($tools as $tool): ?>
        <a href="/tools/<?php echo $tool['slug']; ?>" class="card" style="display:flex;flex-direction:column;text-decoration:none;padding:32px;transition:all 300ms ease;position:relative;overflow:hidden;">
            <div style="position:absolute;top:0;right:0;width:120px;height:120px;border-radius:50%;background:color-mix(in srgb, <?php echo $tool['color']; ?> 8%, transparent);transform:translate(30%,-30%);pointer-events:none;"></div>
            
            <div style="display:flex;align-items:flex-start;gap:20px;margin-bottom:20px;">
                <div style="width:56px;height:56px;border-radius:16px;display:grid;place-items:center;background:color-mix(in srgb, <?php echo $tool['color']; ?> 15%, transparent);color:<?php echo $tool['color']; ?>;flex-shrink:0;font-size:24px;">
                    <?php
                    $icons = [
                        'tag' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>',
                        'bar-chart' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
                        'code' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
                        'file-text' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>',
                        'search' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
                        'link' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>',
                    ];
                    echo $icons[$tool['icon']] ?? '';
                    ?>
                </div>
                <div style="flex:1;min-width:0;">
                    <h2 style="font-size:1.2rem;margin:0 0 6px;color:var(--text);"><?php echo \App\Helpers\SEO::esc($tool['name']); ?></h2>
                    <p style="font-size:14px;color:var(--text-secondary);line-height:1.6;margin:0;"><?php echo \App\Helpers\SEO::esc($tool['description']); ?></p>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:8px;margin-top:auto;">
                <span style="font-size:14px;font-weight:600;color:var(--primary);">Use Tool</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </div>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Benefits Section -->
    <div style="background:var(--bg-secondary);border-radius:var(--radius-2xl);padding:48px;margin-bottom:48px;">
        <h2 style="font-size:1.75rem;margin:0 0 32px;text-align:center;">Why Use Our Tools?</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(250px, 1fr));gap:20px;">
            <div style="text-align:center;padding:24px;">
                <div style="font-size:32px;margin-bottom:12px;">🆓</div>
                <h3 style="font-size:1.05rem;margin:0 0 8px;">100% Free</h3>
                <p style="font-size:14px;color:var(--text-secondary);line-height:1.6;margin:0;">No hidden charges, no premium tiers. All tools are completely free forever.</p>
            </div>
            <div style="text-align:center;padding:24px;">
                <div style="font-size:32px;margin-bottom:12px;">🔒</div>
                <h3 style="font-size:1.05rem;margin:0 0 8px;">Privacy First</h3>
                <p style="font-size:14px;color:var(--text-secondary);line-height:1.6;margin:0;">All analysis happens in your browser. We never store your data.</p>
            </div>
            <div style="text-align:center;padding:24px;">
                <div style="font-size:32px;margin-bottom:12px;">⚡</div>
                <h3 style="font-size:1.05rem;margin:0 0 8px;">Lightning Fast</h3>
                <p style="font-size:14px;color:var(--text-secondary);line-height:1.6;margin:0;">Get instant results. No waiting, no queues, no bloat.</p>
            </div>
        </div>
    </div>

    <!-- FAQ -->
    <div style="max-width:700px;margin:0 auto;">
        <h2 style="font-size:1.5rem;margin:0 0 24px;text-align:center;">Frequently Asked Questions</h2>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <?php
            $faqs = [
                ['q' => 'Are all SEO tools really free?', 'a' => 'Yes! Every tool on this page is completely free to use with no usage limits, premium tiers, or hidden charges.'],
                ['q' => 'Do I need to create an account?', 'a' => 'No. You can use any tool immediately without signing up or creating an account.'],
                ['q' => 'Can I use these tools for client work?', 'a' => 'Absolutely. Use our tools for your own sites, client projects, or any commercial work. No attribution needed.'],
                ['q' => 'How accurate are the results?', 'a' => 'Our tools use industry-standard algorithms and are regularly tested against leading SEO platforms for accuracy.'],
                ['q' => 'Which tool should I use first?', 'a' => 'Start with the SEO Analyzer to get a complete overview of your site\'s SEO health, then use specific tools based on the issues found.'],
            ];
            foreach ($faqs as $faq): ?>
            <details style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;">
                <summary style="padding:20px 24px;font-weight:600;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;list-style:none;">
                    <?php echo \App\Helpers\SEO::esc($faq['q']); ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;transition:transform 200ms ease;"><polyline points="6 9 12 15 18 9"/></svg>
                </summary>
                <div style="padding:0 24px 20px;font-size:14px;color:var(--text-secondary);line-height:1.7;"><?php echo \App\Helpers\SEO::esc($faq['a']); ?></div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</div>
