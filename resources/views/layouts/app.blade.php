<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Syamama Kitchen - Healthy & Natural, pesan langsung via WhatsApp!">
    <title>@yield('title', 'Syamama Kitchen - Healthy & Natural')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
</head>
<body>
    <!-- Floating Food Background -->
    <div class="floating-foods" id="floatingFoods">
        <span>🍰</span><span>🍪</span><span>🥐</span><span>🧁</span>
        <span>🍩</span><span>🎂</span><span>🍞</span><span>🌮</span>
    </div>

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Syamama Kitchen" class="brand-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
            <span style="display:none; font-family:'Poppins',sans-serif; font-weight:700; font-size:1.3rem; color:var(--primary);">Syamama Kitchen</span>
        </a>

        <button class="mobile-toggle" onclick="toggleMenu()">☰</button>

        <ul class="navbar-menu" id="navMenu">
            <li><a href="{{ request()->is('/') ? '#beranda' : route('home') . '#beranda' }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
            <li><a href="{{ request()->is('/') ? '#tentang' : route('home') . '#tentang' }}">Tentang Kami</a></li>
            <li><a href="{{ request()->is('/') ? '#testimoni' : route('home') . '#testimoni' }}">Testimoni</a></li>
            <li><a href="{{ request()->is('/') ? '#faq' : route('home') . '#faq' }}">FAQ</a></li>
            <li><a href="{{ request()->is('/') ? '#kontak' : route('home') . '#kontak' }}">Kontak</a></li>
            <li class="mobile-shop-link"><a href="{{ route('products.index') }}" class="btn-shop-mobile {{ request()->routeIs('products.*') ? 'active' : '' }}">Belanja Sekarang</a></li>
        </ul>

        <a href="{{ route('products.index') }}" class="btn-shop {{ request()->routeIs('products.*') ? 'active' : '' }}">Belanja Sekarang</a>
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
            <div class="footer-simple">
                <span class="footer-copyright">&copy; 2026 Syamama Kitchen.</span>
            </div>
        </div>
    </footer>

    <!-- Floating Action Buttons -->
    <div class="floating-actions">
        <a href="{{ route('cart.index') }}" class="fab fab-cart" id="fabCart" title="Keranjang">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
            @php
                // Clean cart: only count valid products
                $cart = session('cart', []);
                $validCount = 0;
                foreach ($cart as $id => $item) {
                    $product = \App\Models\Product::find($id);
                    if ($product && $product->is_active) {
                        $validCount++;
                    }
                }
            @endphp
            @if($validCount > 0)
                <span class="fab-count">{{ $validCount }}</span>
            @endif
        </a>
        <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}" target="_blank" class="fab fab-wa" id="fabWa" title="Chat WhatsApp">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.3-5.1-3.7-10.6-6.5z"/></svg>
        </a>
    </div>

    <script>
        function toggleMenu() {
            document.getElementById('navMenu').classList.toggle('open');
        }
        
        // Auto close mobile menu when any link is clicked
        document.addEventListener('DOMContentLoaded', function() {
            const navMenu = document.getElementById('navMenu');
            const navLinks = navMenu.querySelectorAll('a');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Close menu on mobile when any link is clicked
                    if (window.innerWidth <= 768) {
                        navMenu.classList.remove('open');
                    }
                });
            });
        });
        
        // Navbar active state untuk anchor links
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');

            // Show WA fab after hero is covered by content-overlay
            const fabWa = document.getElementById('fabWa');
            const hero = document.querySelector('.hero');
            const contactSection = document.getElementById('kontak');
            
            if (hero) {
                const heroBottom = hero.getBoundingClientRect().bottom;
                if (heroBottom <= 60) fabWa.classList.add('visible');
                else fabWa.classList.remove('visible');
            }

            // Hide FAB WA when contact section is visible
            if (contactSection && fabWa) {
                const contactRect = contactSection.getBoundingClientRect();
                const windowHeight = window.innerHeight;
                
                // If contact section is in viewport (visible)
                if (contactRect.top < windowHeight && contactRect.bottom > 0) {
                    fabWa.classList.add('hide-on-contact');
                } else {
                    fabWa.classList.remove('hide-on-contact');
                }
            }

            // Active state untuk navbar saat di section kontak, faq, testimoni, tentang, atau beranda
            const navLinks = document.querySelectorAll('.navbar-menu a');
            const berandaSection = document.getElementById('beranda');
            const tentangSection = document.getElementById('tentang');
            const testimoniSection = document.getElementById('testimoni');
            const faqSection = document.getElementById('faq');
            
            let currentSection = 'beranda';
            
            // Check Tentang section
            if (tentangSection) {
                const tentangRect = tentangSection.getBoundingClientRect();
                if (tentangRect.top < 200 && tentangRect.bottom > 200) {
                    currentSection = 'tentang';
                }
            }
            
            // Check Testimoni section
            if (testimoniSection) {
                const testimoniRect = testimoniSection.getBoundingClientRect();
                if (testimoniRect.top < 200 && testimoniRect.bottom > 200) {
                    currentSection = 'testimoni';
                }
            }
            
            // Check FAQ section
            if (faqSection) {
                const faqRect = faqSection.getBoundingClientRect();
                if (faqRect.top < 200 && faqRect.bottom > 200) {
                    currentSection = 'faq';
                }
            }
            
            // Check Contact section (has priority over FAQ)
            if (contactSection) {
                const contactRect = contactSection.getBoundingClientRect();
                if (contactRect.top < 200 && contactRect.bottom > 200) {
                    currentSection = 'kontak';
                }
            }
            
            // Update active states
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href.includes('#kontak')) {
                    link.classList.toggle('active', currentSection === 'kontak');
                } else if (href.includes('#faq')) {
                    link.classList.toggle('active', currentSection === 'faq');
                } else if (href.includes('#testimoni')) {
                    link.classList.toggle('active', currentSection === 'testimoni');
                } else if (href.includes('#tentang')) {
                    link.classList.toggle('active', currentSection === 'tentang');
                } else if (href.includes('#beranda')) {
                    link.classList.toggle('active', currentSection === 'beranda');
                }
            });
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
        
        // Scroll reveal - repeats
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('revealed');
                else entry.target.classList.remove('revealed');
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.product-card, .card, .section-header').forEach(el => {
            el.classList.add('scroll-reveal');
            revealObserver.observe(el);
        });
    </script>
    @yield('scripts')
</body>
</html>
