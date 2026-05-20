<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Panel - Syamama Kitchen">
    <title>@yield('title', 'Admin') - Syamama Kitchen</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚙️</text></svg>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body style="background:var(--bg-cream);">
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <span class="brand-icon">🍳</span>
                <div>
                    <div>Syamama Kitchen</div>
                    <div style="font-size:0.7rem; font-family:Nunito; color:rgba(255,255,255,0.5);">Admin Panel</div>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li class="menu-label">Menu Utama</li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        📊 Dashboard
                    </a>
                </li>

                <li class="menu-label">Kelola Toko</li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        📁 Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        📦 Produk
                    </a>
                </li>

                <li class="menu-label">Transaksi</li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        🧾 Pesanan
                    </a>
                </li>

                <li class="menu-label">Lainnya</li>
                <li>
                    <a href="{{ route('home') }}">🏠 Lihat Toko</a>
                </li>
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        👋 Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                </li>
            </ul>
        </aside>

        <!-- Content -->
        <div class="admin-content">
            <button class="mobile-toggle" onclick="document.getElementById('adminSidebar').classList.toggle('open')" style="display:none; margin-bottom:1rem;">
                ☰ Menu
            </button>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => {
                el.style.transition = 'all 0.5s ease';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 4000);

        // Mobile sidebar toggle
        if (window.innerWidth <= 768) {
            document.querySelector('.mobile-toggle').style.display = 'block';
        }
    </script>
    @yield('scripts')
</body>
</html>
