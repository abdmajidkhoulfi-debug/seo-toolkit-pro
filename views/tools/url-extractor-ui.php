<div style="max-width:800px;margin:0 auto;">
    <div style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;">
        <button class="btn btn-sm btn-primary" onclick="switchUEMode('url')" id="ueUrlMode">Extract from URL</button>
        <button class="btn btn-sm btn-secondary" onclick="switchUEMode('html')" id="ueHtmlMode">Paste HTML</button>
    </div>

    <div id="ueUrlInput">
        <label class="form-label">Enter URL</label>
        <input type="url" id="ueUrl" class="form-input" placeholder="https://example.com/page">
    </div>

    <div id="ueHtmlInput" style="display:none;">
        <label class="form-label">Paste HTML</label>
        <textarea id="ueHtml" class="form-textarea" rows="8" placeholder="Paste HTML with links..."></textarea>
    </div>

    <button class="btn btn-primary" onclick="extractUrls()" style="margin-top:16px;margin-bottom:24px;">Extract URLs</button>

    <div id="ueResults" style="display:none;">
        <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:16px;margin-bottom:24px;">
            <div class="stat-card" style="text-align:center;">
                <div style="font-size:28px;font-weight:800;color:var(--primary);" id="ueTotal">0</div>
                <div style="font-size:13px;color:var(--text-muted);">Total Links</div>
            </div>
            <div class="stat-card" style="text-align:center;">
                <div style="font-size:28px;font-weight:800;color:var(--success);" id="ueInternal">0</div>
                <div style="font-size:13px;color:var(--text-muted);">Internal</div>
            </div>
            <div class="stat-card" style="text-align:center;">
                <div style="font-size:28px;font-weight:800;color:var(--secondary);" id="ueExternal">0</div>
                <div style="font-size:13px;color:var(--text-muted);">External</div>
            </div>
            <div class="stat-card" style="text-align:center;">
                <div style="font-size:28px;font-weight:800;color:var(--warning);" id="ueNofollow">0</div>
                <div style="font-size:13px;color:var(--text-muted);">Nofollow</div>
            </div>
        </div>

        <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
            <button class="btn btn-sm btn-primary" onclick="filterUE('all')" id="filterAll">All</button>
            <button class="btn btn-sm btn-secondary" onclick="filterUE('internal')" id="filterInternal">Internal</button>
            <button class="btn btn-sm btn-secondary" onclick="filterUE('external')" id="filterExternal">External</button>
            <button class="btn btn-sm btn-secondary" onclick="filterUE('nofollow')" id="filterNofollow">Nofollow</button>
        </div>

        <div class="responsive-table">
            <table class="admin-table">
                <thead>
                    <tr><th>URL</th><th>Anchor Text</th><th>Type</th><th>Rel</th></tr>
                </thead>
                <tbody id="ueTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
let ueMode = 'url';
let ueAllLinks = [];

function switchUEMode(mode) {
    ueMode = mode;
    document.getElementById('ueUrlInput').style.display = mode === 'url' ? 'block' : 'none';
    document.getElementById('ueHtmlInput').style.display = mode === 'html' ? 'block' : 'none';
    document.getElementById('ueUrlMode').className = mode === 'url' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-secondary';
    document.getElementById('ueHtmlMode').className = mode === 'html' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-secondary';
}

async function extractUrls() {
    let html = '';
    
    if (ueMode === 'url') {
        const url = document.getElementById('ueUrl').value;
        if (!url) { alert('Please enter a URL'); return; }
        try {
            const resp = await fetch(url);
            html = await resp.text();
        } catch(e) {
            alert('Failed to fetch URL. Try pasting HTML directly.');
            return;
        }
    } else {
        html = document.getElementById('ueHtml').value;
        if (!html) { alert('Please paste HTML content'); return; }
    }

    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    const links = doc.querySelectorAll('a[href]');
    
    ueAllLinks = Array.from(links).map(a => {
        const href = a.getAttribute('href').trim();
        const text = a.textContent.trim().substring(0, 100);
        const rel = a.getAttribute('rel') || '';
        const isNofollow = rel.includes('nofollow');
        const isInternal = href.startsWith('/') || !href.startsWith('http');
        
        return {
            url: href,
            text: text || '(no text)',
            type: isInternal ? 'Internal' : 'External',
            rel: rel || '-',
            nofollow: isNofollow,
            internal: isInternal,
        };
    }).filter(l => l.url && !l.url.startsWith('#'));

    document.getElementById('ueTotal').textContent = ueAllLinks.length;
    document.getElementById('ueInternal').textContent = ueAllLinks.filter(l => l.internal).length;
    document.getElementById('ueExternal').textContent = ueAllLinks.filter(l => !l.internal).length;
    document.getElementById('ueNofollow').textContent = ueAllLinks.filter(l => l.nofollow).length;

    renderUETable(ueAllLinks);
    document.getElementById('ueResults').style.display = 'block';
}

function renderUETable(links) {
    const tbody = document.getElementById('ueTableBody');
    tbody.innerHTML = links.map(l => `
        <tr>
            <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;"><a href="${l.url}" target="_blank" style="color:var(--primary);font-size:13px;">${l.url.substring(0, 50)}${l.url.length > 50 ? '...' : ''}</a></td>
            <td style="font-size:13px;">${l.text.substring(0, 50)}</td>
            <td><span class="badge" style="background:${l.internal ? 'var(--primary-soft)' : 'color-mix(in srgb, var(--secondary) 15%, transparent)'};color:${l.internal ? 'var(--primary)' : 'var(--secondary)'};">${l.type}</span></td>
            <td style="font-size:13px;color:var(--text-muted);">${l.rel}</td>
        </tr>
    `).join('');
}

function filterUE(type) {
    let filtered = ueAllLinks;
    if (type === 'internal') filtered = ueAllLinks.filter(l => l.internal);
    else if (type === 'external') filtered = ueAllLinks.filter(l => !l.internal);
    else if (type === 'nofollow') filtered = ueAllLinks.filter(l => l.nofollow);

    renderUETable(filtered);

    document.querySelectorAll('#ueResults .btn-sm').forEach(btn => {
        btn.className = 'btn btn-sm btn-secondary';
    });
    document.getElementById('filter' + type.charAt(0).toUpperCase() + type.slice(1)).className = 'btn btn-sm btn-primary';
}
</script>
