<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Syamama Kitchen</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card slide-up">
            <div class="auth-header">
                <div class="logo" style="margin-bottom:0.5rem;">
                    <img src="{{ asset('images/logo.png') }}" alt="Syamama Kitchen" style="height:56px; width:auto;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display:none; font-size:2.5rem; font-family:'Poppins',sans-serif; font-weight:800; color:var(--primary);">S</div>
                </div>
                <h2>Admin Panel</h2>
                <p>Masuk untuk mengelola toko</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@syamama.com" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <div class="form-group" style="display:flex; align-items:center; gap:0.5rem;">
                    <input type="checkbox" name="remember" id="remember" style="accent-color:var(--primary);">
                    <label for="remember" class="text-sm">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-full" style="justify-content:center;">
                    Masuk
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-sm text-light">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>
