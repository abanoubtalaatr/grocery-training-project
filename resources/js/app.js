// ── Vendor ────────────────────────────────────────────────────────────────────
import "bootstrap";
import "@fortawesome/fontawesome-free/css/all.min.css";

// ── App modules ───────────────────────────────────────────────────────────────
import "./modules/axios";

import { initSidebar } from "./modules/sidebar";
import { initTheme   } from "./modules/theme";

// ── Bootstrap on DOM ready ────────────────────────────────────────────────────
document.addEventListener("DOMContentLoaded", () => {
  initSidebar();
  initTheme();
});



