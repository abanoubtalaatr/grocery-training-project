// Bootstrap JS (Collapse, Dropdown, Modal, etc.)
import "bootstrap";

// Font Awesome
import "@fortawesome/fontawesome-free/css/all.min.css";

// Axios
import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// ─── Init on DOM ready ────────────────────────────────────────────────────────
document.addEventListener("DOMContentLoaded", () => {
  initSidebar();
  initDarkMode();
});

// ─── SIDEBAR ──────────────────────────────────────────────────────────────────
function initSidebar() {
  const sidebar     = document.getElementById("admin-sidebar");
  const overlay     = document.getElementById("sidebar-overlay");

  if (!sidebar) return;

  // ── Desktop: restore collapsed state ──
  if (localStorage.getItem("sidebar-collapsed") === "true") {
    sidebar.classList.add("collapsed");
  }

  // ── Desktop collapse btn (inside sidebar footer) ──
  document.getElementById("sidebar-collapse-btn")?.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
    localStorage.setItem("sidebar-collapsed", sidebar.classList.contains("collapsed"));
  });

  // ── Mobile: open via hamburger (any element with this id) ──
  // Use event delegation on body so it works regardless of DOM order
  document.body.addEventListener("click", (e) => {
    if (e.target.closest("#mobile-sidebar-open")) {
      sidebar.classList.add("mobile-open");
      overlay?.classList.add("active");
      document.body.classList.add("sidebar-body-lock");
    }
  });

  // ── Mobile: close via overlay click ──
  overlay?.addEventListener("click", closeMobileSidebar);

  // ── Mobile: close when a nav link is tapped ──
  sidebar.querySelectorAll(".sidebar-item a").forEach((link) => {
    link.addEventListener("click", () => {
      if (window.innerWidth < 992) closeMobileSidebar();
    });
  });

  function closeMobileSidebar() {
    sidebar.classList.remove("mobile-open");
    overlay?.classList.remove("active");
    document.body.classList.remove("sidebar-body-lock");
  }

  // ── Sync chevron with Bootstrap collapse events ──
  document.querySelectorAll(".nav-group-items").forEach((target) => {
    const label = document.querySelector(`[data-bs-target="#${target.id}"]`);
    if (!label) return;

    target.addEventListener("hide.bs.collapse", () => label.classList.add("collapsed"));
    target.addEventListener("show.bs.collapse", () => label.classList.remove("collapsed"));
  });
}

// ─── DARK / LIGHT MODE ───────────────────────────────────────────────────────
function initDarkMode() {
  const html       = document.documentElement;
  const toggleBtns = document.querySelectorAll(".theme-toggle-btn");

  // Apply saved preference (default: dark)
  const saved = localStorage.getItem("theme") ?? "dark";
  applyTheme(saved);

  toggleBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const next = html.dataset.theme === "dark" ? "light" : "dark";
      applyTheme(next);
      localStorage.setItem("theme", next);
    });
  });

  function applyTheme(theme) {
    html.dataset.theme = theme;

    // Sync icons on all toggle buttons
    toggleBtns.forEach((btn) => {
      const icon = btn.querySelector("i");
      if (!icon) return;
      if (theme === "dark") {
        icon.className = "fas fa-sun";
        btn.title = "Switch to light mode";
      } else {
        icon.className = "fas fa-moon";
        btn.title = "Switch to dark mode";
      }
    });
  }
}


