// ─── Theme Toggle (Dark / Light) ───
(function () {
  const html = document.documentElement;
  const saved = localStorage.getItem('sd-theme');

  // Apply saved theme or detect OS preference
  if (saved) {
    html.setAttribute('data-theme', saved);
  } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
    html.setAttribute('data-theme', 'light');
  }

  // Wait for DOM to bind toggle button
  document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('theme-toggle');
    if (!btn) return;

    // Update icon based on current theme
    function updateIcon() {
      const isDark = html.getAttribute('data-theme') !== 'light';
      btn.innerHTML = isDark ? '☀️' : '🌙';
      btn.title = isDark ? 'Passer en mode clair' : 'Passer en mode sombre';
    }

    updateIcon();

    btn.addEventListener('click', function () {
      const isDark = html.getAttribute('data-theme') !== 'light';
      const newTheme = isDark ? 'light' : 'dark';
      html.setAttribute('data-theme', newTheme);
      localStorage.setItem('sd-theme', newTheme);
      updateIcon();

      // Smooth flash animation on click
      btn.style.transform = 'scale(0.8) rotate(180deg)';
      setTimeout(() => { btn.style.transform = ''; }, 300);
    });
  });
})();
