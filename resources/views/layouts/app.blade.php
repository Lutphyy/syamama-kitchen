<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Syamama Kitchen - Kue & Makanan Rumahan Lezat, pesan langsung via WhatsApp!">
    <title>@yield('title', 'Syamama Kitchen 🍰 - Kue & Makanan Rumahan Lezat')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍰</text></svg>">
</head>
<body>
    <!-- Floating Food Background -->
    <div class="floating-foods">
        <span>🍰</span><span>🍪</span><span>🥐</span><span>🧁</span>
        <span>🍩</span><span>🎂</span><span>🍞</span><span>🌮</span>
    </div>

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="{{ route('home') }}" class="navbar-brand">
                <span class="brand-icon">🍳</span>
                Syamama Kitchen
            </a>

            <button class="mobile-toggle" onclick="toggleMenu()">☰</button>

            <ul class="navbar-menu" id="navMenu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">🏠 Beranda</a></li>
                <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">🛍️ Produk</a></li>
                <li>
                    <a href="{{ route('cart.index') }}" class="cart-badge {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                        🛒 Keranjang
                        @php $cartCount = count(session('cart', [])); @endphp
                        @if($cartCount > 0)
                            <span class="cart-count">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
                @auth
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" class="btn-nav">⚙️ Admin</a></li>
                    @endif
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm">👋 Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="btn-nav">🔑 Masuk</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container" style="margin-top:1rem;">
            <div class="alert alert-success">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="container" style="margin-top:1rem;">
            <div class="alert alert-error">{{ session('error') }}</div>
        </div>
    @endif

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h3>🍳 Syamama Kitchen</h3>
                    <p>Menyajikan kue dan makanan rumahan dengan cita rasa istimewa. Dibuat dengan cinta dan bahan-bahan berkualitas! 💛</p>
                    <p style="margin-top:1rem;">📱 WhatsApp: <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}">{{ env('WHATSAPP_NUMBER') }}</a></p>
                </div>
                <div>
                    <h3>Menu</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">🏠 Beranda</a></li>
                        <li><a href="{{ route('products.index') }}">🛍️ Semua Produk</a></li>
                        <li><a href="{{ route('cart.index') }}">🛒 Keranjang</a></li>
                    </ul>
                </div>
                <div>
                    <h3>Info</h3>
                    <ul class="footer-links">
                        <li>📍 Lokasi: Banjarmasin</li>
                        <li>⏰ Buka: 08:00 - 21:00</li>
                        <li>📦 Pengiriman: COD / GoSend</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Syamama Kitchen. Made with ❤️ & 🍰</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            document.getElementById('navMenu').classList.toggle('open');
        }
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => {
                el.style.transition = 'all 0.5s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 500);
            });
        }, 4000);
    </script>
    @yield('scripts')
</body>
</html>
