<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.3.1/flatly/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { width: 200px; background: #ffffff; border-right:1px solid rgba(0,0,0,0.04); height:100vh; position:sticky; top:0; overflow:auto; box-shadow: 0 4px 18px rgba(32,40,55,0.04); padding-top:0.75rem; }
        .content { flex: 1; }
        .table-actions a { margin-left: .5rem; }
        .topbar { background: #2b8aef; color: #fff; padding: .75rem 1rem; }
        .card-stat { border-left: 4px solid #2b8aef; }
        .badge-status { font-size: .85rem; }
        .sidebar .nav-link { color: #374151; padding: .5rem .6rem; border-radius: .5rem; transition: background .15s ease, color .15s ease; position:relative; display:flex; align-items:center; flex-direction:row-reverse; gap:.6rem; }
        .sidebar .nav-link:hover { background: rgba(59,130,246,0.06); color: #1e40af; }
        .sidebar .nav-link.active { font-weight: 700; color: #1e40af; background: rgba(59,130,246,0.06); }
        .sidebar .nav-link.active::after { content: ''; position:absolute; right:0; top:10px; bottom:10px; width:4px; background: #1e40af; border-top-right-radius:4px; border-bottom-right-radius:4px; }
        .sidebar .nav-icon { width: 1.6rem; text-align: center; display:inline-block; }
        .sidebar .nav-label { display:block; font-size:0.95rem; }
        .nav .nav-item + .nav-item { margin-top: .45rem; }
        .sidebar .logo { display:flex; align-items:center; gap:.6rem; padding: .6rem .6rem; margin-bottom: .6rem; }
        .sidebar .logo .brand { font-weight:800; font-size:1.05rem; color:#173a6a }
        .sidebar .search { padding: .4rem .6rem; }
        .sidebar .search input { width:100%; border-radius:8px; border:1px solid rgba(0,0,0,0.06); padding:.45rem .5rem; }
        .sidebar .section-title { font-size:.78rem; color:#6b7280; padding: .6rem .6rem; text-transform:uppercase; letter-spacing:.04em }
        .sidebar .submenu { padding-left:0; }
        .sidebar .submenu .nav-link { padding: .45rem .8rem; font-size:.95rem }
        .sidebar .nav-link i { color: #2b8aef; opacity: .9; }
        .sidebar .profile { border-bottom: 1px solid rgba(0,0,0,0.04); padding-bottom: .75rem; margin-bottom: .75rem; }
        @media (max-width: 991px) { .sidebar { display:none; position:fixed; z-index:1050; right:0; } .sidebar.show { display:block; } }
    </style>
</head>
<body>
    <div class="topbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light d-md-none" id="toggleSidebar">☰</button>
            <h5 class="mb-0">لوحة الإدارة — {{ config('app.name') }}</h5>
        </div>
        <div></div>
    </div>

    <div class="d-flex">
        <nav class="sidebar p-3">
            <!-- sidebar header removed per request -->
            <div class="logo d-flex align-items-center px-2 mb-3">
                <div style="width:36px;height:36px;border-radius:6px;background:linear-gradient(135deg,#2b8aef,#7cc0ff);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700">G</div>
                <div class="brand fw-bold ms-1">{{ config('app.name') ?: 'Grocery' }}</div>
            </div>

            <ul class="nav flex-column px-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <span class="nav-icon"><i class="bi bi-speedometer2" style="font-size:1.05rem"></i></span>
                        <span class="nav-label">الصفحة الرئيسية</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <span class="nav-icon"><i class="bi bi-tags" style="font-size:1.05rem"></i></span>
                        <span class="nav-label">الأقسام</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                        <span class="nav-icon"><i class="bi bi-bag-check" style="font-size:1.05rem"></i></span>
                        <span class="nav-label">الطلبات</span>
                    </a>
                </li>
              
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.settings') || request()->routeIs('admin.settings.edit') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                        <span class="nav-icon"><i class="bi bi-gear" style="font-size:1.05rem"></i></span>
                        <span class="nav-label">الإعدادات</span>
                    </a>
                </li>
            </ul>

 
       
        </nav>
        <main class="content p-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <nav aria-label="breadcrumb">
                @yield('breadcrumb')
            </nav>

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('toggleSidebar')?.addEventListener('click', function(){
            const s = document.querySelector('.sidebar');
            if(!s) return; s.classList.toggle('show');
        });
        // close sidebar when clicking outside (mobile)
        document.addEventListener('click', function(e){
            const s = document.querySelector('.sidebar');
            const btn = document.getElementById('toggleSidebar');
            if(!s || !btn) return;
            if(window.innerWidth <= 991 && s.classList.contains('show')){
                if(!s.contains(e.target) && !btn.contains(e.target)) s.classList.remove('show');
            }
        });
        // no submenu script for simplified sidebar
    </script>
</body>
</html>
