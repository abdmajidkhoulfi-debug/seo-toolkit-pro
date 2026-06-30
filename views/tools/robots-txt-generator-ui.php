<div style="max-width:800px;margin:0 auto;">
    <div style="margin-bottom:24px;">
        <label class="form-label">User-Agent</label>
        <select id="rtUserAgent" class="form-select" style="max-width:300px;">
            <option value="*">All (Google, Bing, etc.)</option>
            <option value="Googlebot">Googlebot</option>
            <option value="Googlebot-Image">Googlebot Image</option>
            <option value="Googlebot-News">Googlebot News</option>
            <option value="Googlebot-Video">Googlebot Video</option>
            <option value="Bingbot">Bingbot</option>
            <option value="Slurp">Yahoo Slurp</option>
            <option value="DuckDuckBot">DuckDuckBot</option>
            <option value="Baiduspider">Baiduspider</option>
            <option value="YandexBot">YandexBot</option>
        </select>
    </div>

    <div style="display:flex;gap:12px;margin-bottom:24px;">
        <button class="btn btn-sm btn-primary" onclick="addRule('allow')">+ Allow Path</button>
        <button class="btn btn-sm btn-secondary" onclick="addRule('disallow')">+ Disallow Path</button>
    </div>

    <div id="rtRules" style="margin-bottom:24px;">
        <div style="display:flex;flex-direction:column;gap:8px;">
            <!-- Rules will be added here -->
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
        <div>
            <label class="form-label">Crawl Delay (seconds)</label>
            <input type="number" id="rtDelay" class="form-input" value="5" min="0" max="60" step="1">
        </div>
        <div>
            <label class="form-label">Sitemap URL</label>
            <input type="url" id="rtSitemap" class="form-input" placeholder="https://example.com/sitemap.xml">
        </div>
    </div>

    <button class="btn btn-primary" onclick="generateRobots()" style="margin-bottom:24px;">Generate Robots.txt</button>

    <div id="rtOutput" style="display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <label class="form-label" style="margin:0;">Generated robots.txt</label>
            <div style="display:flex;gap:8px;">
                <button class="btn btn-sm btn-secondary" onclick="copyRobots()">Copy</button>
                <button class="btn btn-sm btn-secondary" onclick="downloadRobots()">Download</button>
            </div>
        </div>
        <pre id="rtCode" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;font-size:13px;line-height:1.6;overflow-x:auto;margin:0;white-space:pre-wrap;"></pre>
    </div>
</div>

<style>
.rule-item { display:flex;align-items:center;gap:8px;padding:10px 16px;background:var(--bg-secondary);border-radius:var(--radius); }
.rule-item .rule-path { flex:1;padding:8px 12px;border:1px solid var(--border);border-radius:6px;background:var(--surface);font-size:14px; }
.rule-item .rule-path:focus { outline:none;border-color:var(--primary); }
</style>

<script>
let ruleCount = 0;

function addRule(type) {
    ruleCount++;
    const container = document.querySelector('#rtRules > div');
    const item = document.createElement('div');
    item.className = 'rule-item';
    item.dataset.ruleId = ruleCount;
    item.innerHTML = `
        <span style="font-weight:600;font-size:13px;color:${type === 'allow' ? 'var(--success)' : 'var(--error)'};min-width:70px;">${type === 'allow' ? 'Allow:' : 'Disallow:'}</span>
        <input type="text" class="rule-path" placeholder="/path/to/${type === 'allow' ? 'allow' : 'block'}" value="/">
        <button onclick="this.parentElement.remove()" style="background:none;border:none;color:var(--error);cursor:pointer;font-size:16px;">✕</button>
    `;
    container.appendChild(item);
}

function generateRobots() {
    const userAgent = document.getElementById('rtUserAgent').value;
    const delay = document.getElementById('rtDelay').value;
    const sitemap = document.getElementById('rtSitemap').value;

    let robots = `User-agent: ${userAgent}\n`;

    document.querySelectorAll('.rule-item').forEach(item => {
        const isAllow = item.querySelector('span').textContent.trim() === 'Allow:';
        const path = item.querySelector('.rule-path').value.trim();
        if (path) {
            robots += `${isAllow ? 'Allow' : 'Disallow'}: ${path}\n`;
        }
    });

    if (delay > 0) robots += `Crawl-delay: ${delay}\n`;
    if (sitemap) robots += `\nSitemap: ${sitemap}\n`;

    document.getElementById('rtCode').textContent = robots;
    document.getElementById('rtOutput').style.display = 'block';
}

function copyRobots() {
    navigator.clipboard.writeText(document.getElementById('rtCode').textContent);
    const btn = event.target;
    btn.textContent = 'Copied!';
    setTimeout(() => btn.textContent = 'Copy', 2000);
}

function downloadRobots() {
    const blob = new Blob([document.getElementById('rtCode').textContent], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = 'robots.txt'; a.click();
    URL.revokeObjectURL(url);
}
</script>
