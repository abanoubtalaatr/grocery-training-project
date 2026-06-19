<nav class="navbar navbar-expand-lg shadow-sm" id="main-navbar">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
      <span class="navbar-brand-icon">
        <i class="fas fa-shopping-basket"></i>
      </span>
      <span class="navbar-brand-text">Grocery <span class="text-amber">Dashboard</span></span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center gap-1">
        <li class="nav-item">
          <a class="nav-link nav-link-custom active" aria-current="page" href="{{ route('dashboard') }}">
            <i class="fas fa-home me-1"></i>Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-custom" href="#">
            <i class="fas fa-box me-1"></i>Products
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-custom" href="#">
            <i class="fas fa-users me-1"></i>Customers
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-custom nav-link-disabled" href="#" tabindex="-1" aria-disabled="true">
            <i class="fas fa-cog me-1"></i>Settings
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<style>
  #main-navbar {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #1e293b 100%);
    border-bottom: 1px solid rgba(167, 139, 250, 0.2);
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
  }

  .navbar-brand-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    border-radius: 10px;
    color: #1e1b4b;
    font-size: 0.9rem;
    box-shadow: 0 4px 12px rgba(251, 191, 36, 0.35);
  }

  .navbar-brand-text {
    color: #f1f5f9;
    font-size: 1.15rem;
    letter-spacing: 0.01em;
  }

  .text-amber {
    color: #fbbf24;
  }

  .nav-link-custom {
    color: rgba(226, 232, 240, 0.85) !important;
    font-weight: 500;
    font-size: 0.9rem;
    padding: 0.45rem 0.85rem !important;
    border-radius: 8px;
    transition: background 0.2s ease, color 0.2s ease;
    position: relative;
  }

  .nav-link-custom:hover {
    color: #ffffff !important;
    background: rgba(255, 255, 255, 0.1);
  }

  .nav-link-custom.active {
    color: #fbbf24 !important;
    background: rgba(251, 191, 36, 0.12);
  }

  .nav-link-custom.nav-link-disabled {
    color: rgba(148, 163, 184, 0.45) !important;
    cursor: not-allowed;
  }

  .navbar-toggler:focus {
    box-shadow: none;
  }
</style>
