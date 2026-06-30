/**
 * PFSRV SEO - Main JavaScript
 */

(function () {
  'use strict';

  // ==================== THEME TOGGLE ====================
  const themeToggle = document.getElementById('themeToggle');
  const html = document.documentElement;

  function getPreferredTheme() {
    const stored = localStorage.getItem('theme');
    if (stored) return stored;
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  function setTheme(theme) {
    html.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    if (themeToggle) {
      themeToggle.innerHTML = theme === 'dark'
        ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>'
        : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3A7 7 0 0 0 21 12.79Z"/></svg>';
    }
  }

  setTheme(getPreferredTheme());

  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      const current = html.getAttribute('data-theme');
      setTheme(current === 'dark' ? 'light' : 'dark');
    });
  }

  // Watch system preference changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
      setTheme(e.matches ? 'dark' : 'light');
    }
  });

  // ==================== MOBILE MENU ====================
  const menuToggle = document.getElementById('menuToggle');
  const mobileNav = document.getElementById('mobileNav');
  const mobileBackdrop = document.getElementById('mobileBackdrop');
  const menuClose = document.getElementById('menuClose');

  function toggleMenu(open) {
    if (!mobileNav) return;
    mobileNav.classList.toggle('open', open);
    mobileNav.setAttribute('aria-hidden', !open);
    if (menuToggle) menuToggle.setAttribute('aria-expanded', open);
    document.body.style.overflow = open ? 'hidden' : '';
  }

  if (menuToggle) {
    menuToggle.addEventListener('click', () => toggleMenu(true));
  }

  if (menuClose) {
    menuClose.addEventListener('click', () => toggleMenu(false));
  }

  if (mobileBackdrop) {
    mobileBackdrop.addEventListener('click', () => toggleMenu(false));
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') toggleMenu(false);
  });

  // ==================== HEADER SCROLL BEHAVIOR ====================
  const siteHeader = document.getElementById('siteHeader');
  let lastScroll = 0;

  if (siteHeader) {
    window.addEventListener('scroll', () => {
      const currentScroll = window.pageYOffset;
      if (currentScroll > lastScroll && currentScroll > 100) {
        siteHeader.classList.add('hide');
      } else {
        siteHeader.classList.remove('hide');
      }
      lastScroll = currentScroll;
    }, { passive: true });
  }

  // ==================== NEWSLETTER FORM ====================
  const newsletterForms = document.querySelectorAll('.newsletter-form');
  newsletterForms.forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = form.querySelector('input[type="email"]');
      const submitBtn = form.querySelector('button[type="submit"]');
      const message = form.querySelector('.newsletter-message');

      if (!email || !email.value) return;

      submitBtn.disabled = true;
      submitBtn.textContent = 'Subscribing...';

      try {
        const response = await fetch('/subscribe', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ email: email.value }),
        });

        const data = await response.json();

        if (message) {
          message.textContent = data.message;
          message.className = `newsletter-message ${data.success ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}`;
          message.classList.remove('hidden');
        }

        if (data.success) {
          email.value = '';
        }
      } catch (err) {
        if (message) {
          message.textContent = 'Something went wrong. Please try again.';
          message.className = 'newsletter-message text-red-600 dark:text-red-400';
          message.classList.remove('hidden');
        }
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Subscribe';
      }
    });
  });

  // ==================== SMOOTH SCROLL ====================
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (href === '#') return;
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        const headerOffset = 80;
        const elementPosition = target.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
        window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
      }
    });
  });

  // ==================== TOOL COPY BUTTONS ====================
  document.querySelectorAll('.copy-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const targetId = this.getAttribute('data-target');
      const target = document.getElementById(targetId);
      if (!target) return;

      const text = target.textContent || target.value;

      navigator.clipboard.writeText(text).then(() => {
        const original = this.textContent;
        this.textContent = 'Copied!';
        setTimeout(() => { this.textContent = original; }, 2000);
      }).catch(() => {
        // Fallback
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        const original = this.textContent;
        this.textContent = 'Copied!';
        setTimeout(() => { this.textContent = original; }, 2000);
      });
    });
  });

  // ==================== ACTIVE NAV LINK ====================
  const currentPath = window.location.pathname;
  document.querySelectorAll('.nav-link').forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPath || (currentPath.startsWith(href) && href !== '/')) {
      link.classList.add('active');
    } else if (href === '/' && currentPath === '/') {
      link.classList.add('active');
    }
  });

  // ==================== LAZY LOADING ====================
  if ('loading' in HTMLImageElement.prototype) {
    document.querySelectorAll('img[loading="lazy"]').forEach(img => {
      img.src = img.dataset.src || img.src;
    });
  }

  // ==================== CONSOLE (friendly) ====================
  console.log('%c PFSRV SEO %c Professional SEO Toolkit ',
    'background:#6366f1;color:#fff;padding:4px 8px;border-radius:4px 0 0 4px;font-weight:bold;',
    'background:#1e293b;color:#fff;padding:4px 8px;border-radius:0 4px 4px 0;'
  );

})();
