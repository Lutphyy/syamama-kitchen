@extends('layouts.app')
@section('title', 'Syamama Kitchen - Healthy & Natural')
    
@section('content')
<!-- Hero -->
<div class="hero-wrapper" id="beranda">
<section class="hero" id="heroSection">
    <div class="container">
        <!-- Hero Image - Left Side -->
        <div class="hero-image">
            <img src="{{ asset('images/hero.png') }}" alt="Syamama Kitchen">
        </div>
        
        <!-- Hero Content - Right Side -->
        <div class="hero-content slide-up">
            <h1 class="hero-title">
                <span style="color:#047FD5;">Syamama</span><br>
                <span style="color:#FA7302;">Kitchen</span>
            </h1>
            <p class="hero-subtitle">Healthy Drink, Natural Dessert for a Better You</p>
            <div class="hero-actions">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Lihat Menu</a>
                <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}" class="btn btn-lg btn-whatsapp" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="width:18px;height:18px;fill:currentColor;"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.3-5.1-3.7-10.6-6.5z"/></svg>
                    Chat WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>
</div>

<!-- Product Showcase -->
@if($products->count() > 0)
<section class="section" style="background:var(--bg-warm); padding:4rem 0;">
    <div class="container">
        <div class="showcase-layout">
            <!-- Left: Title & Description -->
            <div class="showcase-header fade-in">
                <h2 class="showcase-title">
                    <span style="color:#047FD5;">Product</span><br>
                    <span style="color:#FA7302;">Showcase</span>
                </h2>
                <p class="showcase-desc">Kombinasi terbaik dari bahan alami pilihan yang menemani harimu dengan sehat</p>
            </div>

            <!-- Right: Product Cards Carousel -->
            <div class="showcase-carousel-wrapper">
                <button class="carousel-btn carousel-prev" onclick="slideShowcase(-1)">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:24px;height:24px;">
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                    </svg>
                </button>
                
                <div class="showcase-cards" id="showcaseCards">
                    @foreach($products as $index => $product)
                        <a href="{{ route('products.show', $product->slug) }}" class="showcase-card fade-in" style="animation-delay:{{ $index * 0.1 }}s;">
                            <div class="showcase-card-bg {{ $index % 2 == 0 ? 'bg-orange' : 'bg-blue' }}">
                                <h3 class="showcase-card-title">{{ strtoupper($product->name) }}</h3>
                                <div class="showcase-card-image">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                    @else
                                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.5);font-size:0.8rem;">No Image</div>
                                    @endif
                                    <div class="showcase-card-overlay"></div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                
                <button class="carousel-btn carousel-next" onclick="slideShowcase(1)">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:24px;height:24px;">
                        <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Why Choose Us Section -->
<section class="section" style="background:var(--bg-warm);">
    <div class="container">
        <div class="showcase-layout showcase-layout-reverse">
            <!-- Left side: Feature Cards -->
            <div class="feature-cards">
                <div class="feature-card fade-in" style="animation-delay:0s;">
                    <div class="feature-icon" style="color:#047FD5;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:48px;height:48px;"><path d="M12 3L4 9v12h16V9l-8-6zm0 2.236L18 9.708V19H6V9.708L12 5.236zM8 13h8v2H8v-2z"/></svg>
                    </div>
                    <h3>100% Homemade</h3>
                    <p>Semua dibuat fresh dari dapur rumah, tanpa pengawet berbahaya!</p>
                </div>

                <div class="feature-card fade-in" style="animation-delay:0.1s;">
                    <div class="feature-icon" style="color:#FA7302;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:48px;height:48px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.94s4.18 1.36 4.18 3.85c0 1.89-1.44 2.98-3.12 3.19z"/></svg>
                    </div>
                    <h3>Harga Bersahabat</h3>
                    <p>Kualitas premium tapi harga tetap terjangkau. Nggak bikin kantong bolong!</p>
                </div>

                <div class="feature-card fade-in" style="animation-delay:0.2s;">
                    <div class="feature-icon" style="color:#047FD5;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" style="width:48px;height:48px;"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.3-5.1-3.7-10.6-6.5z"/></svg>
                    </div>
                    <h3>Order Gampang</h3>
                    <p>Pilih, checkout, langsung chat WhatsApp. Sesimpel itu!</p>
                </div>

                <div class="feature-card fade-in" style="animation-delay:0.3s;">
                    <div class="feature-icon" style="color:#FA7302;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:48px;height:48px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    </div>
                    <h3>Sehat</h3>
                    <p>Menggunakan bahan alami pilihan yang kaya nutrisi untuk kesehatan keluarga!</p>
                </div>
            </div>

            <!-- Right side: Title & Description -->
            <div class="showcase-header fade-in">
                <h2 class="showcase-title">
                    <span style="color:#047FD5;">Why</span><br>
                    <span style="color:#FA7302;">Choose Us</span>
                </h2>
                <p class="showcase-desc">Alasan kenapa kamu harus cobain produk kami!</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="section" id="testimoni" style="background:var(--bg-warm);">
    <div class="container">
        <div class="section-header-center fade-in">
            <h2 class="showcase-title">
                <span style="color:#047FD5;">Ulasan</span><br>
                <span style="color:#FA7302;">Pelanggan</span>
            </h2>
            <p class="showcase-desc">Beginilah cerita kepuasan dari pelanggan.</p>
        </div>

        <div class="testimonials-grid">
            <div class="testimonial-card fade-in" style="animation-delay:0s;">
                <div class="testimonial-text">
                    <p>Testimoni 1</p>
                </div>
                <div class="testimonial-author">
                    <h4>Nama 1</h4>
                    <span>Pekerjaan 1</span>
                </div>
            </div>

            <div class="testimonial-card fade-in" style="animation-delay:0.1s;">
                <div class="testimonial-text">
                    <p>Testimoni 2</p>
                </div>
                <div class="testimonial-author">
                    <h4>Nama 2</h4>
                    <span>Pekerjaan 2</span>
                </div>
            </div>

            <div class="testimonial-card fade-in" style="animation-delay:0.2s;">
                <div class="testimonial-text">
                    <p>Testimoni 3</p>
                </div>
                <div class="testimonial-author">
                    <h4>Nama 3</h4>
                    <span>Pekerjaan 3</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section" id="faq" style="background:var(--bg-warm);">
    <div class="container">
        <div class="faq-layout">
            <div class="faq-header fade-in">
                <h2 class="showcase-title">
                    <span style="color:#047FD5;">Pertanyaan yang Sering</span><br>
                    <span style="color:#FA7302;">Ditanyakan</span>
                </h2>
                <p class="showcase-desc">Jawaban atas pertanyaan seputar produk dan layanan kami.</p>
            </div>

            <div class="faq-container fade-in">
                <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(0)">
                    <span>Question 1</span>
                    <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 10l5 5 5-5z"/>
                    </svg>
                </button>
                <div class="faq-answer">
                    <p>Answer 1</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(1)">
                    <span>Question 2</span>
                    <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 10l5 5 5-5z"/>
                    </svg>
                </button>
                <div class="faq-answer">
                    <p>Answer 2</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(2)">
                    <span>Question 3</span>
                    <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 10l5 5 5-5z"/>
                    </svg>
                </button>
                <div class="faq-answer">
                    <p>Answer 3</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(3)">
                    <span>Question 4</span>
                    <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 10l5 5 5-5z"/>
                    </svg>
                </button>
                <div class="faq-answer">
                    <p>Answer 4</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section" id="kontak">
    <div class="container">
        <div class="section-header">
            <h2><span style="color:#047FD5;">Hubungi</span> <span style="color:#FA7302;">Kami</span></h2>
            <p>Kami siap melayani pesanan dan menjawab pertanyaan Anda!</p>
        </div>

        <div class="contact-grid">
            <!-- Contact Info -->
            <div class="contact-info">
                <div class="contact-item">
                    <div class="contact-icon" style="background:#047FD5;color:white;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:24px;height:24px;">
                            <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <h4>Email</h4>
                        <p><a href="mailto:{{ env('CONTACT_EMAIL') }}" style="color:var(--text-dark);">{{ env('CONTACT_EMAIL') }}</a></p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon" style="background:#FA7302;color:white;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:24px;height:24px;">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <h4>Lokasi</h4>
                        <p>{{ env('LOCATION_ADDRESS') }}</p>
                    </div>
                </div>

                <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}?text={{ urlencode('Halo Syamama Kitchen! Saya mau bertanya...') }}" 
                   class="btn btn-whatsapp btn-lg" target="_blank" style="width:100%;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="width:20px;height:20px;fill:currentColor;"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.3-5.1-3.7-10.6-6.5z"/></svg>
                    Hubungi Kami via WhatsApp
                </a>
            </div>

            <!-- Google Maps Embed -->
            <div class="contact-map">
                <iframe src="{{ env('GMAPS_EMBED') }}"
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi Syamama Kitchen"></iframe>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
// Product Showcase Carousel
let showcaseScrollPosition = 0;

function slideShowcase(direction) {
    const container = document.getElementById('showcaseCards');
    const cardWidth = container.querySelector('.showcase-card').offsetWidth;
    const gap = 24; // 1.5rem gap
    const scrollAmount = (cardWidth + gap) * direction;
    
    container.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
    });
}

// Touch/swipe support for mobile
let showcaseStartX = 0;
let showcaseScrollLeft = 0;

const showcaseContainer = document.getElementById('showcaseCards');
if (showcaseContainer) {
    showcaseContainer.addEventListener('touchstart', (e) => {
        showcaseStartX = e.touches[0].pageX;
        showcaseScrollLeft = showcaseContainer.scrollLeft;
    });

    showcaseContainer.addEventListener('touchmove', (e) => {
        const x = e.touches[0].pageX;
        const walk = (showcaseStartX - x);
        showcaseContainer.scrollLeft = showcaseScrollLeft + walk;
    });
}

// FAQ Accordion
function toggleFaq(index) {
    const faqItems = document.querySelectorAll('.faq-item');
    const clickedItem = faqItems[index];
    const isOpen = clickedItem.classList.contains('active');
    
    // Close all FAQ items
    faqItems.forEach(item => {
        item.classList.remove('active');
    });
    
    // If clicked item was not open, open it
    if (!isOpen) {
        clickedItem.classList.add('active');
    }
}
</script>
@endsection
