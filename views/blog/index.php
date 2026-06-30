<?php
$posts = $posts ?? [];
$categories = $categories ?? [];
$recentPosts = $recentPosts ?? [];
$page = $page ?? 1;
$lastPage = $lastPage ?? 1;
$currentCategory = $currentCategory ?? '';
$currentTag = $currentTag ?? '';
$searchQuery = $searchQuery ?? '';
$category = $category ?? null;
$tag = $tag ?? null;
$config = require __DIR__ . '/../../config/app.php';
$appUrl = $config['app']['url'] ?? '';
?>

<div class="container" style="padding-top:48px;padding-bottom:96px;">
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" style="margin-bottom:24px;">
        <ol style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;list-style:none;padding:0;margin:0;">
            <li style="font-size:14px;color:var(--text-muted);"><a href="/" style="color:var(--text-secondary);">Home</a><span style="margin-left:8px;color:var(--text-muted);">/</span></li>
            <li style="font-size:14px;color:var(--text-muted);" aria-current="page">
                <?php if ($category): ?><a href="/blog" style="color:var(--text-secondary);">Blog</a><span style="margin:0 8px;color:var(--text-muted);">/</span><?php echo \App\Helpers\SEO::esc($category['name']); ?>
                <?php elseif ($tag): ?><a href="/blog" style="color:var(--text-secondary);">Blog</a><span style="margin:0 8px;color:var(--text-muted);">/</span>Tag: <?php echo \App\Helpers\SEO::esc($tag['name']); ?>
                <?php elseif ($searchQuery): ?>Search: <?php echo \App\Helpers\SEO::esc($searchQuery); ?>
                <?php else: ?>Blog<?php endif; ?>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:48px;flex-wrap:wrap;gap:16px;">
        <div>
            <?php if ($category): ?>
            <h1 style="font-size:2.5rem;font-weight:800;letter-spacing:-0.04em;margin:0 0 8px;"><?php echo \App\Helpers\SEO::esc($category['name']); ?></h1>
            <p style="color:var(--text-secondary);margin:0;"><?php echo \App\Helpers\SEO::esc($category['description'] ?? ''); ?></p>
            <?php elseif ($tag): ?>
            <h1 style="font-size:2.5rem;font-weight:800;letter-spacing:-0.04em;margin:0 0 8px;">Tag: <?php echo \App\Helpers\SEO::esc($tag['name']); ?></h1>
            <p style="color:var(--text-secondary);margin:0;">Browse all articles tagged with "<?php echo \App\Helpers\SEO::esc($tag['name']); ?>"</p>
            <?php elseif ($searchQuery): ?>
            <h1 style="font-size:2.5rem;font-weight:800;letter-spacing:-0.04em;margin:0 0 8px;">Search: <?php echo \App\Helpers\SEO::esc($searchQuery); ?></h1>
            <p style="color:var(--text-secondary);margin:0;">Search results for your query</p>
            <?php else: ?>
            <h1 style="font-size:2.5rem;font-weight:800;letter-spacing:-0.04em;margin:0 0 8px;">Blog</h1>
            <p style="color:var(--text-secondary);margin:0;">Expert SEO guides, tutorials, and tips to help you rank better.</p>
            <?php endif; ?>
        </div>
        <form method="GET" action="/blog" style="display:flex;gap:8px;">
            <input type="text" name="search" placeholder="Search articles..." value="<?php echo \App\Helpers\SEO::esc($searchQuery); ?>" class="form-input" style="width:250px;">
            <button type="submit" class="btn btn-sm btn-primary">Search</button>
        </form>
    </div>

    <div style="display:grid;grid-template-columns:260px 1fr;gap:40px;">
        <!-- Sidebar -->
        <aside>
            <div class="card" style="padding:24px;margin-bottom:24px;">
                <h3 style="font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);margin:0 0 16px;">Categories</h3>
                <nav style="display:flex;flex-direction:column;gap:4px;">
                    <a href="/blog" style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;border-radius:8px;font-size:14px;color:<?php echo !$currentCategory ? 'var(--primary)' : 'var(--text-secondary)'; ?>;font-weight:<?php echo !$currentCategory ? '600' : '400'; ?>;background:<?php echo !$currentCategory ? 'var(--primary-soft)' : 'transparent'; ?>;transition:all 200ms ease;">
                        All Posts <span style="font-size:12px;color:var(--text-muted);"></span>
                    </a>
                    <?php foreach ($categories as $cat): ?>
                    <a href="/blog/category/<?php echo \App\Helpers\SEO::esc($cat['slug']); ?>" style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;border-radius:8px;font-size:14px;color:<?php echo $currentCategory === $cat['slug'] ? 'var(--primary)' : 'var(--text-secondary)'; ?>;font-weight:<?php echo $currentCategory === $cat['slug'] ? '600' : '400'; ?>;background:<?php echo $currentCategory === $cat['slug'] ? 'var(--primary-soft)' : 'transparent'; ?>;transition:all 200ms ease;">
                        <?php echo \App\Helpers\SEO::esc($cat['name']); ?>
                        <span style="font-size:12px;color:var(--text-muted);">(<?php echo $cat['post_count']; ?>)</span>
                    </a>
                    <?php endforeach; ?>
                </nav>
            </div>

            <?php if (!empty($recentPosts)): ?>
            <div class="card" style="padding:24px;">
                <h3 style="font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);margin:0 0 16px;">Recent Posts</h3>
                <nav style="display:flex;flex-direction:column;gap:12px;">
                    <?php foreach ($recentPosts as $rp): ?>
                    <a href="/blog/<?php echo \App\Helpers\SEO::esc($rp['slug']); ?>" style="display:block;text-decoration:none;">
                        <div style="font-size:14px;font-weight:600;color:var(--text);line-height:1.4;margin-bottom:4px;"><?php echo \App\Helpers\SEO::esc($rp['title']); ?></div>
                        <div style="font-size:12px;color:var(--text-muted);"><?php echo date('M j, Y', strtotime($rp['published_at'] ?? $rp['created_at'])); ?></div>
                    </a>
                    <?php endforeach; ?>
                </nav>
            </div>
            <?php endif; ?>
        </aside>

        <!-- Main -->
        <div>
            <?php if (empty($posts)): ?>
            <div style="text-align:center;padding:64px 24px;">
                <div style="font-size:48px;margin-bottom:16px;">📝</div>
                <h2 style="font-size:1.5rem;margin:0 0 8px;">No Articles Found</h2>
                <p style="color:var(--text-secondary);margin:0 0 24px;">No articles match your current filter. Try a different category or search term.</p>
                <a href="/blog" class="btn btn-primary">View All Posts</a>
            </div>
            <?php else: ?>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                <?php foreach ($posts as $post): ?>
                <a href="/blog/<?php echo \App\Helpers\SEO::esc($post['slug']); ?>" class="card" style="padding:0;overflow:hidden;text-decoration:none;display:flex;flex-direction:column;">
                    <?php if (!empty($post['featured_image'])): ?>
                    <img src="<?php echo \App\Helpers\SEO::esc($post['featured_image']); ?>" alt="<?php echo \App\Helpers\SEO::esc($post['alt_text'] ?: $post['title']); ?>" style="width:100%;height:200px;object-fit:cover;" loading="lazy">
                    <?php else: ?>
                    <div style="width:100%;height:200px;background:linear-gradient(135deg, var(--primary-soft), color-mix(in srgb, var(--primary) 20%, transparent));display:grid;place-items:center;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    </div>
                    <?php endif; ?>
                    <div style="padding:24px;flex:1;display:flex;flex-direction:column;">
                        <div style="display:flex;gap:8px;margin-bottom:10px;flex-wrap:wrap;">
                            <?php if (!empty($post['category_name'])): ?>
                            <span class="badge"><?php echo \App\Helpers\SEO::esc($post['category_name']); ?></span>
                            <?php endif; ?>
                            <span style="font-size:13px;color:var(--text-muted);"><?php echo date('M j, Y', strtotime($post['published_at'] ?? $post['created_at'])); ?></span>
                        </div>
                        <h2 style="font-size:1.2rem;letter-spacing:-0.03em;margin:0 0 8px;line-height:1.3;"><?php echo \App\Helpers\SEO::esc($post['title']); ?></h2>
                        <p style="font-size:14px;color:var(--text-secondary);line-height:1.6;margin:0 0 auto;"><?php echo \App\Helpers\SEO::esc($post['excerpt'] ?: strip_tags(mb_substr($post['content'], 0, 150))); ?></p>
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:16px;">
                            <span style="font-size:13px;color:var(--text-muted);">By <?php echo \App\Helpers\SEO::esc($post['author']); ?></span>
                            <span style="font-size:14px;font-weight:600;color:var(--primary);">Read →</span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($lastPage > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                <a href="<?php echo buildPaginationUrl($page - 1, $currentCategory, $currentTag, $searchQuery); ?>">← Previous</a>
                <?php endif; ?>
                <?php for ($i = max(1, $page - 2); $i <= min($lastPage, $page + 2); $i++): ?>
                <a href="<?php echo buildPaginationUrl($i, $currentCategory, $currentTag, $searchQuery); ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $lastPage): ?>
                <a href="<?php echo buildPaginationUrl($page + 1, $currentCategory, $currentTag, $searchQuery); ?>">Next →</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
function buildPaginationUrl($page, $category, $tag, $search) {
    $params = [];
    if ($page > 1) $params['page'] = $page;
    if ($category) return "/blog/category/{$category}?" . ($page > 1 ? "page={$page}" : '');
    if ($tag) return "/blog/tag/{$tag}?" . ($page > 1 ? "page={$page}" : '');
    if ($search) $params['search'] = $search;
    return '/blog' . (!empty($params) ? '?' . http_build_query($params) : '');
}
?>
