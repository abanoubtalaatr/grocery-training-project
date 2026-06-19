/**
 * sidebar.js
 * Handles all sidebar behaviour:
 *  - Desktop collapse / expand (persisted in localStorage)
 *  - Mobile drawer open / close via hamburger + overlay
 *  - Chevron rotation sync with Bootstrap collapse events
 */
export function initSidebar() {
  const sidebar = document.getElementById("admin-sidebar");
  const overlay = document.getElementById("sidebar-overlay");

  if (!sidebar) return;

  // ── Restore desktop collapsed state ────────────────────────────────────────
  if (localStorage.getItem("sidebar-collapsed") === "true") {
    sidebar.classList.add("collapsed");
  }

  // ── Desktop: collapse / expand button ──────────────────────────────────────
  document
    .getElementById("sidebar-collapse-btn")
    ?.addEventListener("click", () => {
      sidebar.classList.toggle("collapsed");
      localStorage.setItem(
        "sidebar-collapsed",
        sidebar.classList.contains("collapsed")
      );
    });

  // ── Mobile: open via hamburger ─────────────────────────────────────────────
  // Event delegation on <body> so DOM render order doesn't matter
  document.body.addEventListener("click", (e) => {
    if (e.target.closest("#mobile-sidebar-open")) {
      openMobileSidebar();
    }
  });

  // ── Mobile: close via overlay tap ─────────────────────────────────────────
  overlay?.addEventListener("click", closeMobileSidebar);

  // ── Mobile: close when a nav link is tapped ────────────────────────────────
  sidebar.querySelectorAll(".sidebar-item a").forEach((link) => {
    link.addEventListener("click", () => {
      if (window.innerWidth < 992) closeMobileSidebar();
    });
  });

  // ── Chevron sync with Bootstrap collapse events ────────────────────────────
  document.querySelectorAll(".nav-group-items").forEach((target) => {
    const label = document.querySelector(
      `[data-bs-target="#${target.id}"]`
    );
    if (!label) return;

    target.addEventListener("hide.bs.collapse", () =>
      label.classList.add("collapsed")
    );
    target.addEventListener("show.bs.collapse", () =>
      label.classList.remove("collapsed")
    );
  });

  // ── Helpers ────────────────────────────────────────────────────────────────
  function openMobileSidebar() {
    sidebar.classList.add("mobile-open");
    overlay?.classList.add("active");
    document.body.classList.add("sidebar-body-lock");
  }

  function closeMobileSidebar() {
    sidebar.classList.remove("mobile-open");
    overlay?.classList.remove("active");
    document.body.classList.remove("sidebar-body-lock");
  }
}
