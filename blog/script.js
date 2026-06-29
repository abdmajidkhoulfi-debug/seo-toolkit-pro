(function () {
  'use strict';

  // Theme toggle
  const root = document.documentElement;
  const themeToggle = document.getElementById('themeToggle');
  
  if (themeToggle) {
    let theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    root.setAttribute('data-theme', theme);
    
    function updateThemeIcon(mode) {
      themeToggle.innerHTML = mode === 'dark'
        ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><path d="M12 1V3M12 21V23M4.22 4.22L5.64 5.64M18.36 18.36L19.78 19.78M1 12H3M21 12H23M4.22 19.78L5.64 18.36M18.36 5.64L19.78 4.22"></path></svg>'
        : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3A7 7 0 0 0 21 12.79Z"></path></svg>';
    }
    
    updateThemeIcon(theme);
    
    themeToggle.addEventListener('click', function () {
      theme = theme === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-theme', theme);
      updateThemeIcon(theme);
    });
  }

  // Mobile menu
  const menuToggle = document.getElementById('menuToggle');
  const menuClose = document.getElementById('menuClose');
  const mobileNav = document.getElementById('mobileNav');
  const mobileBackdrop = document.getElementById('mobileBackdrop');

  if (menuToggle && mobileNav) {
    function openMenu() {
      mobileNav.classList.add('open');
      mobileNav.setAttribute('aria-hidden', 'false');
      menuToggle.setAttribute('aria-expanded', 'true');
      document.body.classList.add('menu-open');
    }

    function closeMenu() {
      mobileNav.classList.remove('open');
      mobileNav.setAttribute('aria-hidden', 'true');
      menuToggle.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('menu-open');
    }

    menuToggle.addEventListener('click', openMenu);
    if (menuClose) menuClose.addEventListener('click', closeMenu);
    if (mobileBackdrop) mobileBackdrop.addEventListener('click', closeMenu);
  }

  // Header hide on scroll
  const header = document.getElementById('siteHeader');
  if (header) {
    let lastScroll = 0;
    window.addEventListener('scroll', function () {
      const current = window.pageYOffset || document.documentElement.scrollTop;
      if (current > lastScroll && current > 140) {
        header.classList.add('hide');
      } else {
        header.classList.remove('hide');
      }
      lastScroll = Math.max(current, 0);
    }, { passive: true });
  }

  // Blog search & filter
  const searchInput = document.getElementById('postSearch');
  const categoryFilter = document.getElementById('categoryFilter');
  const postsGrid = document.getElementById('postsGrid');
  const noResults = document.getElementById('noResults');

  if (searchInput && postsGrid) {
    function filterPosts() {
      const query = searchInput.value.toLowerCase().trim();
      const category = categoryFilter ? categoryFilter.value : 'all';
      const cards = postsGrid.querySelectorAll('.post-card');
      let visibleCount = 0;

      cards.forEach(function (card) {
        const title = card.getAttribute('data-title') || '';
        const excerpt = card.getAttribute('data-excerpt') || '';
        const cat = card.getAttribute('data-category') || '';
        const author = card.getAttribute('data-author') || '';

        const matchesSearch = query === '' || title.includes(query) || excerpt.includes(query) || author.includes(query);
        const matchesCategory = category === 'all' || cat === category;

        if (matchesSearch && matchesCategory) {
          card.style.display = '';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      if (noResults) {
        noResults.classList.toggle('hidden', visibleCount > 0);
      }
    }

    searchInput.addEventListener('input', filterPosts);
    if (categoryFilter) categoryFilter.addEventListener('change', filterPosts);
  }

  // Quick filter buttons (sidebar categories)
  document.querySelectorAll('.quick-filter').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const category = btn.getAttribute('data-category');
      if (categoryFilter) {
        categoryFilter.value = category;
        filterPosts();
      }
    });
  });

})();
