<?php
$post = $post ?? [];
$readingTime = $readingTime ?? 0;
$relatedPosts = $relatedPosts ?? [];
$recentPosts = $recentPosts ?? [];
$categories = $categories ?? [];
$comments = $comments ?? [];
$tags = $post['tags'] ?? [];
$config = require __DIR__ . '/../../config/app.php';
$appUrl = $config['app']['url'] ?? '';
$siteName = $siteName ?? 'PFSRV SEO';
?>

<div class="container" style="padding-top:48px;padding-bottom:96px;max-width:900px;">
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" style="margin-bottom:24px;">
        <ol style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;list-style:none;padding:0;margin:0;">
            <li style="font-size:14px;color:var(--text-muted);"><a href="/" style="color:var(--text-secondary);">Home</a><span style="margin-left:8px;color:var(--text-muted);">/</span></li>
            <li style="font-size:14px;color:var(--text-muted);"><a href="/blog" style="color:var(--text-secondary);">Blog</a><span style="margin-left:8px;color:var(--text-muted);">/</span></li>
            <?php if (!empty($post['category_name'])): ?>
            <li style="font-size:14px;color:var(--text-muted);"><a href="/blog/category/<?php echo \App\Helpers\SEO::esc($post['category_slug']); ?>" style="color:var(--text-secondary);"><?php echo \App\Helpers\SEO::esc($post['category_name']); ?></a><span style="margin-left:8px;color:var(--text-muted);">/</span></li>
            <?php endif; ?>
            <li style="font-size:14px;color:var(--text-muted);" aria-current="page"><?php echo \App\Helpers\SEO::esc($post['title']); ?></li>
        </ol>
    </nav>

    <!-- Breadcrumb Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "<?php echo $appUrl; ?>/"},
        {"@type": "ListItem", "position": 2, "name": "Blog", "item": "<?php echo $appUrl; ?>/blog"},
        <?php if (!empty($post['category_name'])): ?>
        {"@type": "ListItem", "position": 3, "name": "<?php echo \App\Helpers\SEO::esc($post['category_name']); ?>", "item": "<?php echo $appUrl; ?>/blog/category/<?php echo \App\Helpers\SEO::esc($post['category_slug']); ?>"},
        {"@type": "ListItem", "position": 4, "name": "<?php echo \App\Helpers\SEO::esc($post['title']); ?>", "item": "<?php echo $appUrl; ?>/blog/<?php echo \App\Helpers\SEO::esc($post['slug']); ?>"}
        <?php else: ?>
        {"@type": "ListItem", "position": 3, "name": "<?php echo \App\Helpers\SEO::esc($post['title']); ?>", "item": "<?php echo $appUrl; ?>/blog/<?php echo \App\Helpers\SEO::esc($post['slug']); ?>"}
        <?php endif; ?>
      ]
    }
    </script>

    <!-- Article Header -->
    <header style="margin-bottom:32px;">
        <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
            <?php if (!empty($post['category_name'])): ?>
            <a href="/blog/category/<?php echo \App\Helpers\SEO::esc($post['category_slug']); ?>" class="badge"><?php echo \App\Helpers\SEO::esc($post['category_name']); ?></a>
            <?php endif; ?>
            <span style="font-size:14px;color:var(--text-muted);display:flex;align-items:center;gap:6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <?php echo $readingTime; ?> min read
            </span>
        </div>

        <h1 style="font-size:2.8rem;font-weight:800;letter-spacing:-0.04em;line-height:1.1;margin:0 0 16px;"><?php echo \App\Helpers\SEO::esc($post['title']); ?></h1>

        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:40px;height:40px;border-radius:50%;background:var(--primary-soft);color:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;"><?php echo strtoupper(substr($post['author'], 0, 1)); ?></div>
                <div>
                    <div style="font-weight:600;font-size:14px;"><?php echo \App\Helpers\SEO::esc($post['author']); ?></div>
                    <div style="font-size:13px;color:var(--text-muted);">Published on <?php echo date('F j, Y', strtotime($post['published_at'] ?? $post['created_at'])); ?></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Featured Image -->
    <?php if (!empty($post['featured_image'])): ?>
    <div style="margin-bottom:32px;border-radius:var(--radius-2xl);overflow:hidden;">
        <img src="<?php echo \App\Helpers\SEO::esc($post['featured_image']); ?>" alt="<?php echo \App\Helpers\SEO::esc($post['alt_text'] ?: $post['title']); ?>" style="width:100%;height:auto;max-height:500px;object-fit:cover;" loading="lazy">
    </div>
    <?php endif; ?>

    <!-- Table of Contents -->
    <div class="toc" id="tableOfContents">
        <div class="toc-title">Table of Contents</div>
        <ul class="toc-list" id="tocList"></ul>
    </div>

    <!-- Article Content -->
    <article class="seo-content" style="font-size:1.05rem;line-height:1.8;color:var(--text-secondary);">
        <?php echo $post['content']; ?>
    </article>

    <!-- Tags -->
    <?php if (!empty($tags)): ?>
    <div style="margin:32px 0;padding-top:24px;border-top:1px solid var(--border);">
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <span style="font-size:14px;font-weight:600;color:var(--text-muted);">Tags:</span>
            <?php foreach ($tags as $tag): ?>
            <a href="/blog/tag/<?php echo \App\Helpers\SEO::esc($tag['slug']); ?>" class="badge badge-secondary"><?php echo \App\Helpers\SEO::esc($tag['name']); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Share Buttons -->
    <div style="margin:32px 0;padding:24px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <span style="font-size:14px;font-weight:600;">Share this article:</span>
            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($post['title']); ?>&url=<?php echo urlencode($appUrl . '/blog/' . $post['slug']); ?>" target="_blank" rel="noopener" class="share-btn" style="background:#1da1f2;color:#fff;">Twitter</a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($appUrl . '/blog/' . $post['slug']); ?>" target="_blank" rel="noopener" class="share-btn" style="background:#4267B2;color:#fff;">Facebook</a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($appUrl . '/blog/' . $post['slug']); ?>&title=<?php echo urlencode($post['title']); ?>" target="_blank" rel="noopener" class="share-btn" style="background:#0077B5;color:#fff;">LinkedIn</a>
            <button class="share-btn" style="background:var(--bg-secondary);color:var(--text);" onclick="navigator.clipboard.writeText('<?php echo $appUrl . '/blog/' . $post['slug']; ?>');this.textContent='Copied!';setTimeout(()=>this.textContent='Copy Link',2000);">Copy Link</button>
        </div>
    </div>

    <!-- Author Bio -->
    <div style="display:flex;align-items:flex-start;gap:16px;padding:24px;background:var(--bg-secondary);border-radius:var(--radius-lg);margin-bottom:32px;">
        <div style="width:56px;height:56px;border-radius:50%;background:var(--primary-soft);color:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:20px;flex-shrink:0;"><?php echo strtoupper(substr($post['author'], 0, 1)); ?></div>
        <div>
            <div style="font-weight:700;font-size:15px;margin-bottom:4px;"><?php echo \App\Helpers\SEO::esc($post['author']); ?></div>
            <p style="font-size:14px;color:var(--text-secondary);line-height:1.6;margin:0;">SEO specialist and content creator at <?php echo \App\Helpers\SEO::esc($siteName); ?>. Passionate about helping websites rank higher and drive organic traffic.</p>
        </div>
    </div>

    <!-- Related Posts -->
    <?php if (!empty($relatedPosts)): ?>
    <div style="margin-bottom:48px;">
        <h2 style="font-size:1.5rem;margin:0 0 24px;">Related Articles</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(250px, 1fr));gap:20px;">
            <?php foreach ($relatedPosts as $rp): ?>
            <a href="/blog/<?php echo \App\Helpers\SEO::esc($rp['slug']); ?>" style="text-decoration:none;display:block;padding:20px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);transition:all 200ms ease;">
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:8px;"><?php echo date('M j, Y', strtotime($rp['published_at'] ?? $rp['created_at'])); ?></div>
                <h3 style="font-size:15px;margin:0;line-height:1.4;"><?php echo \App\Helpers\SEO::esc($rp['title']); ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Comments -->
    <div id="comments" style="margin-bottom:48px;">
        <h2 style="font-size:1.5rem;margin:0 0 24px;">Comments</h2>

        <?php if (!empty($comments)): ?>
        <div style="margin-bottom:32px;">
            <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <div class="comment-meta">
                    <div class="comment-avatar"><?php echo strtoupper(substr($comment['name'], 0, 1)); ?></div>
                    <div>
                        <div style="font-weight:600;font-size:14px;"><?php echo \App\Helpers\SEO::esc($comment['name']); ?></div>
                        <div style="font-size:12px;color:var(--text-muted);"><?php echo date('M j, Y \a\t g:i a', strtotime($comment['created_at'])); ?></div>
                    </div>
                </div>
                <div style="font-size:14px;color:var(--text-secondary);line-height:1.7;"><?php echo $comment['content']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p style="color:var(--text-secondary);margin-bottom:24px;">No comments yet. Be the first to share your thoughts!</p>
        <?php endif; ?>

        <!-- Comment Form -->
        <div class="card" style="padding:32px;">
            <h3 style="font-size:1.1rem;margin:0 0 16px;">Leave a Comment</h3>
            <form id="commentForm" method="POST">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-input" required placeholder="Your name">
                    </div>
                    <div>
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-input" required placeholder="your@email.com">
                    </div>
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label">Website</label>
                    <input type="url" name="website" class="form-input" placeholder="https://yoursite.com (optional)">
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label">Comment *</label>
                    <textarea name="content" class="form-textarea" rows="5" required placeholder="Share your thoughts..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Post Comment</button>
                <p id="commentMessage" style="font-size:14px;margin-top:12px;"></p>
            </form>
        </div>
    </div>

    <!-- CTA -->
    <div style="background:linear-gradient(135deg, var(--primary), var(--secondary));border-radius:var(--radius-2xl);padding:48px;text-align:center;color:#fff;">
        <h2 style="font-size:1.75rem;font-weight:800;margin:0 0 12px;">Try Our Free SEO Tools</h2>
        <p style="font-size:1.05rem;opacity:0.9;max-width:450px;margin:0 auto 24px;">Analyze your website, generate meta tags, and optimize your content with our professional toolkit.</p>
        <a href="/tools/seo-analyzer" class="btn btn-lg" style="background:#fff;color:var(--primary);">Explore SEO Tools →</a>
    </div>
</div>

<script>
// Generate Table of Contents
document.addEventListener('DOMContentLoaded', function() {
    const content = document.querySelector('.seo-content');
    const tocList = document.getElementById('tocList');
    
    if (content && tocList) {
        const headings = content.querySelectorAll('h2, h3');
        if (headings.length === 0) {
            document.getElementById('tableOfContents').style.display = 'none';
            return;
        }

        headings.forEach((h, i) => {
            if (!h.id) h.id = 'heading-' + i;
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = '#' + h.id;
            a.textContent = h.textContent;
            if (h.tagName === 'H3') {
                a.style.paddingLeft = '24px';
            }
            li.appendChild(a);
            tocList.appendChild(li);
        });
    }
});

// Comment form submission
document.getElementById('commentForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    const msg = document.getElementById('commentMessage');

    btn.disabled = true;
    btn.textContent = 'Posting...';

    try {
        const resp = await fetch(window.location.pathname + '/comment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams(formData),
        });
        const data = await resp.json();
        msg.textContent = data.message;
        msg.style.color = data.success ? 'var(--success)' : 'var(--error)';
        if (data.success) this.reset();
    } catch(e) {
        msg.textContent = 'Something went wrong. Please try again.';
        msg.style.color = 'var(--error)';
    } finally {
        btn.disabled = false;
        btn.textContent = 'Post Comment';
    }
});
</script>
