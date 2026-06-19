/**
 * theme.js
 * Handles dark / light mode toggling:
 *  - Reads saved preference from localStorage (default: dark)
 *  - Applies [data-theme] attribute on <html>
 *  - Syncs all .theme-toggle-btn icons and tooltips
 */
export function initTheme() {
  const html       = document.documentElement;
  const toggleBtns = document.querySelectorAll(".theme-toggle-btn");

  // Apply saved preference on page load
  const saved = localStorage.getItem("theme") ?? "dark";
  applyTheme(saved);

  // Wire up every toggle button
  toggleBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const next = html.dataset.theme === "dark" ? "light" : "dark";
      applyTheme(next);
      localStorage.setItem("theme", next);
    });
  });

  /**
   * Apply a theme and update all toggle button icons/titles.
   * @param {"dark"|"light"} theme
   */
  function applyTheme(theme) {
    html.dataset.theme = theme;

    toggleBtns.forEach((btn) => {
      const icon = btn.querySelector("i");
      if (!icon) return;

      if (theme === "dark") {
        icon.className  = "fas fa-sun";
        btn.title       = "Switch to light mode";
      } else {
        icon.className  = "fas fa-moon";
        btn.title       = "Switch to dark mode";
      }
    });
  }
}
