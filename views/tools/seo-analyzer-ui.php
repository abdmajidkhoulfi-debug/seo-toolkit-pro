<div style="max-width:800px;margin:0 auto;">
    <div style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;">
        <button class="btn btn-sm btn-primary" onclick="switchSAMode('url')" id="saUrlMode">Analyze URL</button>
        <button class="btn btn-sm btn-secondary" onclick="switchSAMode('html')" id="saHtmlMode">Paste HTML</button>
    </div>

    <div id="saUrlInput">
        <label class="form-label">Enter URL</label>
        <input type="url" id="saUrl" class="form-input" placeholder="https://example.com/page">
    </div>

    <div id="saHtmlInput" style="display:none;">
        <label class="form-label">Paste HTML</label>
        <textarea id="saHtml" class="form-textarea" rows="8" placeholder="Paste the full HTML of the page..."></textarea>
    </div>

    <button class="btn btn-primary" onclick="analyzeSEO()" style="margin-top:16px;margin-bottom:24px;">Analyze Page</button>

    <div id="saResults" style="display:none;">
        <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:16px;margin-bottom:24px;">
            <div class="stat-card" style="text-align:center;">
                <div style="font-size:28px;font-weight:800;color:var(--primary);" id="saTotalChecks">0</div>
                <div style="font-size:13px;color:var(--text-muted);">Checks Run</div>
            </div>
            <div class="stat-card" style="text-align:center;">
                <div style="font-size:28px;font-weight:800;color:var(--success);" id="saPassed">0</div>
                <div style="font-size:13px;color:var(--text-muted);">Passed</div>
            </div>
            <div class="stat-card" style="text-align:center;">
                <div style="font-size:28px;font-weight:800;color:var(--error);" id="saFailed">0</div>
                <div style="font-size:13px;color:var(--text-muted);">Issues Found</div>
            </div>
        </div>

        <div id="saAuditResults" style="display:flex;flex-direction:column;gap:12px;"></div>
    </div>
</div>

<script>
let saMode = 'url';

function switchSAMode(mode) {
    saMode = mode;
    document.getElementById('saUrlInput').style.display = mode === 'url' ? 'block' : 'none';
    document.getElementById('saHtmlInput').style.display = mode === 'html' ? 'block' : 'none';
    document.getElementById('saUrlMode').className = mode === 'url' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-secondary';
    document.getElementById('saHtmlMode').className = mode === 'html' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-secondary';
}

async function analyzeSEO() {
    let html = '';
    
    if (saMode === 'url') {
        const url = document.getElementById('saUrl').value;
        if (!url) { alert('Please enter a URL'); return; }
        try {
            const resp = await fetch(url);
            html = await resp.text();
        } catch(e) {
            alert('Failed to fetch URL. Try pasting HTML directly.');
            return;
        }
    } else {
        html = document.getElementById('saHtml').value;
        if (!html) { alert('Please paste HTML content'); return; }
    }

    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    const results = [];

    // 1. Title Tag
    const title = doc.querySelector('title');
    results.push({
        name: 'Title Tag',
        status: title && title.textContent.trim().length > 0 ? 'pass' : 'fail',
        detail: title ? `"${title.textContent.trim().substring(0, 70)}" (${title.textContent.trim().length} chars)` : 'Missing title tag',
    });

    // 2. Meta Description
    const desc = doc.querySelector('meta[name="description"]');
    results.push({
        name: 'Meta Description',
        status: desc && desc.getAttribute('content')?.length > 0 ? 'pass' : 'fail',
        detail: desc ? `"${desc.getAttribute('content').substring(0, 70)}..." (${desc.getAttribute('content').length} chars)` : 'Missing meta description',
    });

    // 3. Viewport
    const viewport = doc.querySelector('meta[name="viewport"]');
    results.push({
        name: 'Viewport Meta Tag',
        status: viewport ? 'pass' : 'fail',
        detail: viewport ? 'Present' : 'Missing - required for mobile optimization',
    });

    // 4. H1
    const h1 = doc.querySelector('h1');
    results.push({
        name: 'H1 Heading',
        status: h1 && h1.textContent.trim().length > 0 ? 'pass' : 'warn',
        detail: h1 ? `"${h1.textContent.trim().substring(0, 50)}"` : 'No H1 tag found',
    });

    // 5. Multiple H1s
    const h1s = doc.querySelectorAll('h1');
    if (h1s.length > 1) {
        results.push({
            name: 'Multiple H1 Tags',
            status: 'warn',
            detail: `${h1s.length} H1 tags found - use only one H1 per page`,
        });
    }

    // 6. H2 Headings
    const h2s = doc.querySelectorAll('h2');
    results.push({
        name: 'H2 Headings',
        status: h2s.length > 0 ? 'pass' : 'warn',
        detail: `${h2s.length} H2 tags found`,
    });

    // 7. Images with alt text
    const imgs = doc.querySelectorAll('img:not([role="presentation"])');
    const noAlt = [];
    imgs.forEach(img => { if (!img.getAttribute('alt') && img.getAttribute('alt') !== '') noAlt.push(img); });
    results.push({
        name: 'Image Alt Text',
        status: noAlt.length === 0 ? 'pass' : 'warn',
        detail: imgs.length > 0 ? `${imgs.length - noAlt.length}/${imgs.length} images have alt text` : 'No images found',
    });

    // 8. Open Graph
    const ogTitle = doc.querySelector('meta[property="og:title"]');
    const ogDesc = doc.querySelector('meta[property="og:description"]');
    const ogImage = doc.querySelector('meta[property="og:image"]');
    const ogCount = (ogTitle ? 1 : 0) + (ogDesc ? 1 : 0) + (ogImage ? 1 : 0);
    results.push({
        name: 'Open Graph Tags',
        status: ogCount >= 2 ? 'pass' : ogCount > 0 ? 'warn' : 'fail',
        detail: `${ogCount}/3 required OG tags found (title, description, image)`,
    });

    // 9. Twitter Card
    const twitterCard = doc.querySelector('meta[name="twitter:card"]');
    results.push({
        name: 'Twitter Card',
        status: twitterCard ? 'pass' : 'warn',
        detail: twitterCard ? `Card type: ${twitterCard.getAttribute('content')}` : 'Missing Twitter Card',
    });

    // 10. Canonical
    const canonical = doc.querySelector('link[rel="canonical"]');
    results.push({
        name: 'Canonical URL',
        status: canonical ? 'pass' : 'warn',
        detail: canonical ? canonical.getAttribute('href') : 'No canonical URL',
    });

    // 11. Language
    const htmlEl = doc.querySelector('html');
    results.push({
        name: 'HTML Lang Attribute',
        status: htmlEl && htmlEl.getAttribute('lang') ? 'pass' : 'warn',
        detail: htmlEl?.getAttribute('lang') ? `lang="${htmlEl.getAttribute('lang')}"` : 'Missing lang attribute',
    });

    // 12. Robots meta
    const robots = doc.querySelector('meta[name="robots"]');
    results.push({
        name: 'Robots Meta Tag',
        status: 'pass',
        detail: robots ? robots.getAttribute('content') : 'Default (index, follow)',
    });

    // 13. Structured Data
    const scripts = doc.querySelectorAll('script[type="application/ld+json"]');
    results.push({
        name: 'Structured Data',
        status: scripts.length > 0 ? 'pass' : 'warn',
        detail: `${scripts.length} JSON-LD schema(s) found`,
    });

    // 14. Favicon
    const favicon = doc.querySelector('link[rel*="icon"]');
    results.push({
        name: 'Favicon',
        status: favicon ? 'pass' : 'warn',
        detail: favicon ? 'Present' : 'Missing favicon',
    });

    // 15. Links
    const links = doc.querySelectorAll('a[href]');
    const internal = Array.from(links).filter(a => a.getAttribute('href').startsWith('/') || a.getAttribute('href').startsWith(window.location?.origin || ''));
    results.push({
        name: 'Internal Links',
        status: internal.length > 0 ? 'pass' : 'warn',
        detail: `${internal.length} internal links found`,
    });

    // Display results
    const passed = results.filter(r => r.status === 'pass').length;
    const failed = results.filter(r => r.status !== 'pass').length;
    document.getElementById('saTotalChecks').textContent = results.length;
    document.getElementById('saPassed').textContent = passed;
    document.getElementById('saFailed').textContent = failed;

    const container = document.getElementById('saAuditResults');
    container.innerHTML = results.map(r => `
        <div style="display:flex;align-items:flex-start;gap:12px;padding:16px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);border-left:3px solid ${r.status === 'pass' ? 'var(--success)' : r.status === 'warn' ? 'var(--warning)' : 'var(--error)'};">
            <div style="font-size:18px;flex-shrink:0;">${r.status === 'pass' ? '✅' : r.status === 'warn' ? '⚠️' : '❌'}</div>
            <div style="flex:1;">
                <div style="font-weight:600;font-size:14px;margin-bottom:4px;">${r.name}</div>
                <div style="font-size:13px;color:var(--text-secondary);">${r.detail}</div>
            </div>
        </div>
    `).join('');

    document.getElementById('saResults').style.display = 'block';
}
</script>
