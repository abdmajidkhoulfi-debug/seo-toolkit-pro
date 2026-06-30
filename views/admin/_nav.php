<?php
$currentPath = $_SERVER['REQUEST_URI'];
?>
<nav style="background:var(--surface);border-bottom:1px solid var(--border);">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:60px;">
        <div style="display:flex;align-items:center;gap:24px;">
            <a href="/admin/dashboard" style="display:flex;align-items:center;gap:8px;font-weight:700;color:var(--text);text-decoration:none;">
                <span style="width:32px;height:32px;border-radius:8px;display:grid;place-items:center;color:#fff;background:linear-gradient(135deg,var(--primary),color-mix(in srgb, var(--primary) 60%, #fff));font-weight:800;font-size:14px;">P</span>
                <span style="font-size:15px;">Admin</span>
            </a>
            <div style="display:flex;gap:4px;">
                <a href="/admin/dashboard" style="padding:8px 16px;border-radius:8px;font-size:14px;font-weight:500;color:<?php echo str_contains($currentPath, '/dashboard') ? 'var(--primary)' : 'var(--text-secondary)'; ?>;background:<?php echo str_contains($currentPath, '/dashboard') ? 'var(--primary-soft)' : 'transparent'; ?>;text-decoration:none;">Dashboard</a>
                <a href="/admin/posts" style="padding:8px 16px;border-radius:8px;font-size:14px;font-weight:500;color:<?php echo str_contains($currentPath, '/posts') ? 'var(--primary)' : 'var(--text-secondary)'; ?>;background:<?php echo str_contains($currentPath, '/posts') ? 'var(--primary-soft)' : 'transparent'; ?>;text-decoration:none;">Posts</a>
                <a href="/admin/categories" style="padding:8px 16px;border-radius:8px;font-size:14px;font-weight:500;color:<?php echo str_contains($currentPath, '/categories') ? 'var(--primary)' : 'var(--text-secondary)'; ?>;background:<?php echo str_contains($currentPath, '/categories') ? 'var(--primary-soft)' : 'transparent'; ?>;text-decoration:none;">Categories</a>
                <a href="/admin/comments" style="padding:8px 16px;border-radius:8px;font-size:14px;font-weight:500;color:<?php echo str_contains($currentPath, '/comments') ? 'var(--primary)' : 'var(--text-secondary)'; ?>;background:<?php echo str_contains($currentPath, '/comments') ? 'var(--primary-soft)' : 'transparent'; ?>;text-decoration:none;">Comments</a>
                <a href="/admin/subscribers" style="padding:8px 16px;border-radius:8px;font-size:14px;font-weight:500;color:<?php echo str_contains($currentPath, '/subscribers') ? 'var(--primary)' : 'var(--text-secondary)'; ?>;background:<?php echo str_contains($currentPath, '/subscribers') ? 'var(--primary-soft)' : 'transparent'; ?>;text-decoration:none;">Subscribers</a>
                <a href="/admin/media" style="padding:8px 16px;border-radius:8px;font-size:14px;font-weight:500;color:<?php echo str_contains($currentPath, '/media') ? 'var(--primary)' : 'var(--text-secondary)'; ?>;background:<?php echo str_contains($currentPath, '/media') ? 'var(--primary-soft)' : 'transparent'; ?>;text-decoration:none;">Media</a>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="/" target="_blank" style="font-size:13px;color:var(--primary);text-decoration:none;">View Site ↗</a>
            <a href="/admin/logout" style="font-size:13px;color:var(--text-muted);text-decoration:none;">Logout</a>
        </div>
    </div>
</nav>
