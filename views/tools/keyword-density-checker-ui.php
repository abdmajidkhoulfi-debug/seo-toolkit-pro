<div style="max-width:800px;margin:0 auto;">
    <div style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;">
        <button class="btn btn-sm <?php echo empty($mode) || $mode === 'text' ? 'btn-primary' : 'btn-secondary'; ?>" onclick="switchKDMode('text')" id="kdTextMode">Paste Text</button>
        <button class="btn btn-sm btn-secondary" onclick="switchKDMode('url')" id="kdUrlMode">Analyze URL</button>
    </div>

    <div id="kdTextInput">
        <label class="form-label">Paste Your Content</label>
        <textarea id="kdContent" class="form-textarea" rows="8" placeholder="Paste your article, blog post, or webpage content here..."></textarea>
    </div>

    <div id="kdUrlInput" style="display:none;">
        <label class="form-label">Enter URL</label>
        <input type="url" id="kdUrl" class="form-input" placeholder="https://example.com/page">
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin:16px 0 24px;">
        <div>
            <label class="form-label">N-Gram Size</label>
            <select id="kdNgram" class="form-select">
                <option value="1">1 Word</option>
                <option value="2" selected>2 Words</option>
                <option value="3">3 Words</option>
            </select>
        </div>
        <div>
            <label class="form-label">Min Frequency</label>
            <input type="number" id="kdMinFreq" class="form-input" value="2" min="1" max="100">
        </div>
        <div>
            <label class="form-label">Filter Stop Words</label>
            <select id="kdStopWords" class="form-select">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
    </div>

    <button class="btn btn-primary" onclick="analyzeKeywords()" style="margin-bottom:24px;">Analyze Keywords</button>

    <div id="kdResults" style="display:none;">
        <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:16px;margin-bottom:24px;">
            <div class="stat-card" style="text-align:center;"><div style="font-size:28px;font-weight:800;color:var(--primary);" id="kdTotalWords">0</div><div style="font-size:13px;color:var(--text-muted);">Total Words</div></div>
            <div class="stat-card" style="text-align:center;"><div style="font-size:28px;font-weight:800;color:var(--primary);" id="kdUniqueWords">0</div><div style="font-size:13px;color:var(--text-muted);">Unique Words</div></div>
            <div class="stat-card" style="text-align:center;"><div style="font-size:28px;font-weight:800;color:var(--primary);" id="kdPhrases">0</div><div style="font-size:13px;color:var(--text-muted);">Phrases Found</div></div>
            <div class="stat-card" style="text-align:center;"><div style="font-size:28px;font-weight:800;color:var(--primary);" id="kdAvgDensity">0%</div><div style="font-size:13px;color:var(--text-muted);">Avg Density</div></div>
        </div>

        <div style="background:var(--bg-secondary);border-radius:var(--radius-lg);padding:20px;margin-bottom:24px;">
            <canvas id="kdChart" height="250"></canvas>
        </div>

        <div class="responsive-table">
            <table class="admin-table">
                <thead>
                    <tr><th>Keyword / Phrase</th><th>Frequency</th><th>Density</th><th>Trend</th></tr>
                </thead>
                <tbody id="kdTableBody"></tbody>
            </table>
        </div>

        <button class="btn btn-sm btn-secondary" onclick="exportKDCSV()" style="margin-top:16px;">Export CSV</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
let kdChartInstance = null;
let currentMode = 'text';

function switchKDMode(mode) {
    currentMode = mode;
    document.getElementById('kdTextInput').style.display = mode === 'text' ? 'block' : 'none';
    document.getElementById('kdUrlInput').style.display = mode === 'url' ? 'block' : 'none';
    document.getElementById('kdTextMode').className = mode === 'text' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-secondary';
    document.getElementById('kdUrlMode').className = mode === 'url' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-secondary';
}

const stopWords = new Set(['the','a','an','and','or','but','in','on','at','to','for','of','by','with','from','is','are','was','were','be','been','being','have','has','had','do','does','did','will','would','could','should','may','might','shall','can','need','dare','ought','used','this','that','these','those','i','me','my','myself','we','our','ours','ourselves','you','your','yours','yourself','yourselves','he','him','his','himself','she','her','hers','herself','it','its','itself','they','them','their','theirs','themselves','what','which','who','whom','this','that','these','those','am','is','are','was','were','be','been','being','have','has','had','having','do','does','did','doing','would','should','could','ought','might','shall','need','dare','will','may','can','use','used','using','a','an','the','and','but','if','or','because','as','until','while','of','at','by','for','with','about','against','between','into','through','during','before','after','above','below','to','from','up','down','in','out','on','off','over','under','again','further','then','once','here','there','when','where','why','how','all','any','both','each','few','more','most','other','some','such','no','nor','not','only','own','same','so','than','too','very','just','because','as','until','while','about','between','through','during','before','after','above','below','again','further','then','once','here','there','when','where','why','how','both','each','few','more','most','other','some','such','no','nor','not','only','own','same','so','than','too','very']);

async function analyzeKeywords() {
    let text = currentMode === 'text' ? document.getElementById('kdContent').value : '';
    
    if (currentMode === 'url') {
        const url = document.getElementById('kdUrl').value;
        if (!url) { alert('Please enter a URL'); return; }
        try {
            const resp = await fetch(url);
            const html = await resp.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            doc.querySelectorAll('script, style, nav, header, footer, iframe').forEach(el => el.remove());
            text = doc.body?.textContent || '';
        } catch(e) {
            alert('Failed to fetch URL. Try pasting text directly.');
            return;
        }
    }

    if (!text.trim()) { alert('Please enter some content to analyze'); return; }

    const ngram = parseInt(document.getElementById('kdNgram').value);
    const minFreq = parseInt(document.getElementById('kdMinFreq').value);
    const filterStop = document.getElementById('kdStopWords').value === '1';

    // Process text
    const words = text.toLowerCase().match(/[a-z0-9]+(?:[''][a-z]+)?/g) || [];
    document.getElementById('kdTotalWords').textContent = words.length;
    
    const unique = new Set(words.filter(w => !filterStop || !stopWords.has(w)));
    document.getElementById('kdUniqueWords').textContent = unique.size;

    // Build n-grams
    const phrases = {};
    for (let i = 0; i <= words.length - ngram; i++) {
        let phrase = words.slice(i, i + ngram).join(' ');
        if (filterStop && phrase.split(' ').every(w => stopWords.has(w))) continue;
        phrases[phrase] = (phrases[phrase] || 0) + 1;
    }

    // Filter by min frequency
    const filtered = Object.entries(phrases)
        .filter(([, count]) => count >= minFreq)
        .sort((a, b) => b[1] - a[1]);

    document.getElementById('kdPhrases').textContent = filtered.length;
    
    const totalPhrases = Object.values(phrases).reduce((a, b) => a + b, 0);
    const avgDensity = totalPhrases > 0 ? ((filtered.reduce((a, [, c]) => a + c, 0) / totalPhrases) * 100).toFixed(1) : 0;
    document.getElementById('kdAvgDensity').textContent = avgDensity + '%';

    // Update chart
    const top10 = filtered.slice(0, 10);
    if (kdChartInstance) kdChartInstance.destroy();
    
    const ctx = document.getElementById('kdChart').getContext('2d');
    kdChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: top10.map(([p]) => p.length > 20 ? p.substring(0, 20) + '...' : p),
            datasets: [{
                label: 'Frequency',
                data: top10.map(([, c]) => c),
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { ticks: { maxRotation: 45 } }
            }
        }
    });

    // Update table
    const tbody = document.getElementById('kdTableBody');
    tbody.innerHTML = filtered.map(([phrase, count], i) => {
        const density = totalPhrases > 0 ? ((count / totalPhrases) * 100).toFixed(2) : 0;
        return `<tr>
            <td style="font-weight:600;">${phrase}</td>
            <td>${count}</td>
            <td>${density}%</td>
            <td>${i < 3 ? '🔥' : i < 7 ? '📈' : '📊'}</td>
        </tr>`;
    }).join('');

    document.getElementById('kdResults').style.display = 'block';
}

function exportKDCSV() {
    const rows = [['Keyword', 'Frequency', 'Density']];
    document.querySelectorAll('#kdTableBody tr').forEach(tr => {
        const cells = tr.querySelectorAll('td');
        rows.push([cells[0].textContent, cells[1].textContent, cells[2].textContent]);
    });
    const csv = rows.map(r => r.join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = 'keyword-density.csv'; a.click();
    URL.revokeObjectURL(url);
}
</script>
