<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-3 border-orange shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-orange" href="#">
      <i class="fas fa-shopping-basket me-2"></i>Grocery Dashboard
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active text-orange" aria-current="page" href="{{ route('dashboard') }}">
            <i class="fas fa-home me-1"></i>Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <i class="fas fa-box me-1"></i>Products
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <i class="fas fa-users me-1"></i>Customers
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
            <i class="fas fa-cog me-1"></i>Settings
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
