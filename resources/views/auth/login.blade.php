<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Syamama Kitchen 🔑</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍰</text></svg>">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card slide-up">
            <div class="auth-header">
                <div class="logo">🍳</div>
                <h2>Selamat Datang!</h2>
                <p>Masuk ke akun Syamama Kitchen kamu</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">📧 Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@contoh.com" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">🔒 Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <div class="form-group" style="display:flex; align-items:center; gap:0.5rem;">
                    <input type="checkbox" name="remember" id="remember" style="accent-color:var(--primary);">
                    <label for="remember" class="text-sm">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-full" style="justify-content:center;">
                    🔑 Masuk
                </button>
            </form>

            <div class="text-center mt-3">
                <p class="text-sm text-light">Belum punya akun? <a href="{{ route('register') }}" class="text-primary font-bold">Daftar di sini! 🎉</a></p>
            </div>

            <div class="text-center mt-2">
                <a href="{{ route('home') }}" class="text-sm text-light">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>
