<div style="max-width:800px;margin:0 auto;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">
        <div>
            <label class="form-label">Page Title</label>
            <input type="text" id="metaTitle" class="form-input" placeholder="Enter your page title" maxlength="70" oninput="updateMetaPreview()">
            <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--text-muted);margin-top:4px;">
                <span id="titleCount">0</span>
                <span id="titleStatus" style="font-weight:600;">Good: 50-60 chars</span>
            </div>
        </div>
        <div>
            <label class="form-label">Meta Description</label>
            <textarea id="metaDescription" class="form-textarea" placeholder="Enter your meta description" maxlength="320" rows="3" oninput="updateMetaPreview()" style="min-height:80px;"></textarea>
            <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--text-muted);margin-top:4px;">
                <span id="descCount">0</span>
                <span id="descStatus" style="font-weight:600;">Ideal: 150-160 chars</span>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">
        <div>
            <label class="form-label">Keywords (comma separated)</label>
            <input type="text" id="metaKeywords" class="form-input" placeholder="keyword1, keyword2, keyword3">
        </div>
        <div>
            <label class="form-label">Canonical URL</label>
            <input type="url" id="metaCanonical" class="form-input" placeholder="https://example.com/page">
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">
        <div>
            <label class="form-label">Robots</label>
            <select id="metaRobots" class="form-select">
                <option value="index, follow">Index, Follow</option>
                <option value="noindex, follow">No Index, Follow</option>
                <option value="index, nofollow">Index, No Follow</option>
                <option value="noindex, nofollow">No Index, No Follow</option>
            </select>
        </div>
        <div>
            <label class="form-label">Content Type</label>
            <select id="metaOgType" class="form-select">
                <option value="website">Website</option>
                <option value="article">Article</option>
                <option value="product">Product</option>
                <option value="profile">Profile</option>
            </select>
        </div>
    </div>

    <!-- SERP Preview -->
    <div style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;margin-bottom:24px;">
        <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:12px;">Google SERP Preview</div>
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:16px;">
            <div id="serpUrl" style="font-size:14px;color:#1a0dab;margin-bottom:2px;">https://example.com/page</div>
            <div id="serpTitle" style="font-size:20px;color:#1a0dab;font-weight:400;line-height:1.3;margin-bottom:2px;text-decoration:none;">Page Title - Site Name</div>
            <div id="serpDesc" style="font-size:14px;color:#545454;line-height:1.58;">Your meta description will appear here in Google search results.</div>
        </div>
    </div>

    <!-- Generated Code -->
    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <label class="form-label" style="margin:0;">Generated HTML</label>
            <button class="btn btn-sm btn-secondary" onclick="copyMetaTags()">Copy Code</button>
        </div>
        <pre id="metaOutput" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;font-size:13px;line-height:1.6;overflow-x:auto;margin:0;white-space:pre-wrap;"></pre>
    </div>
</div>

<script>
function updateMetaPreview() {
    const title = document.getElementById('metaTitle').value;
    const desc = document.getElementById('metaDescription').value;
    const canonical = document.getElementById('metaCanonical').value || 'https://example.com/page';
    const keywords = document.getElementById('metaKeywords').value;
    const robots = document.getElementById('metaRobots').value;
    const ogType = document.getElementById('metaOgType').value;

    // Update counts
    document.getElementById('titleCount').textContent = title.length;
    document.getElementById('descCount').textContent = desc.length;

    // Title status
    const titleStatus = document.getElementById('titleStatus');
    if (title.length < 30) titleStatus.textContent = 'Too short: 50-60 chars';
    else if (title.length <= 60) titleStatus.textContent = '✅ Good length';
    else if (title.length <= 70) titleStatus.textContent = '⚠️ Consider shortening';
    else titleStatus.textContent = '❌ Too long';

    // Description status
    const descStatus = document.getElementById('descStatus');
    if (desc.length < 120) descStatus.textContent = 'Too short: 150-160 chars';
    else if (desc.length <= 160) descStatus.textContent = '✅ Ideal length';
    else if (desc.length <= 320) descStatus.textContent = '⚠️ Consider shortening';
    else descStatus.textContent = '❌ Too long';

    // SERP Preview
    const siteName = '<?php echo \App\Helpers\SEO::esc($siteName ?? 'PFSRV SEO'); ?>';
    document.getElementById('serpTitle').textContent = title ? `${title} - ${siteName}` : `Page Title - ${siteName}`;
    document.getElementById('serpUrl').textContent = canonical;
    document.getElementById('serpDesc').textContent = desc || 'Your meta description will appear here in Google search results.';

    // Generate HTML
    let html = `<!-- Primary Meta Tags -->\n`;
    html += `<title>${title ? title + ' - ' + siteName : ''}</title>\n`;
    html += `<meta name="description" content="${desc}">\n`;
    if (keywords) html += `<meta name="keywords" content="${keywords}">\n`;
    html += `<meta name="robots" content="${robots}">\n`;
    if (canonical) html += `<link rel="canonical" href="${canonical}">\n\n`;

    html += `<!-- Open Graph / Facebook -->\n`;
    html += `<meta property="og:type" content="${ogType}">\n`;
    html += `<meta property="og:title" content="${title || 'Page Title'}">\n`;
    html += `<meta property="og:description" content="${desc || ''}">\n`;
    html += `<meta property="og:url" content="${canonical}">\n`;
    html += `<meta property="og:site_name" content="${siteName}">\n\n`;

    html += `<!-- Twitter -->\n`;
    html += `<meta name="twitter:card" content="summary_large_image">\n`;
    html += `<meta name="twitter:title" content="${title || 'Page Title'}">\n`;
    html += `<meta name="twitter:description" content="${desc || ''}">\n`;

    document.getElementById('metaOutput').textContent = html;
}

function copyMetaTags() {
    const text = document.getElementById('metaOutput').textContent;
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.querySelector('#metaOutput + div button');
        if (btn) { btn.textContent = 'Copied!'; setTimeout(() => btn.textContent = 'Copy Code', 2000); }
    });
}

// Initial update
updateMetaPreview();
</script>
