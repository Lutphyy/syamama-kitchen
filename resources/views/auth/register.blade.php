<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Syamama Kitchen 🎉</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍰</text></svg>">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card slide-up">
            <div class="auth-header">
                <div class="logo">🎉</div>
                <h2>Gabung Yuk!</h2>
                <p>Buat akun untuk pengalaman belanja yang lebih baik</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">👤 Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Nama kamu" required>
                </div>

                <div class="form-group">
                    <label class="form-label">📧 Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@contoh.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">📱 No. Telepon (opsional)</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="081234567890">
                </div>

                <div class="form-group">
                    <label class="form-label">🔒 Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="form-group">
                    <label class="form-label">🔒 Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-full" style="justify-content:center;">
                    🎉 Daftar Sekarang
                </button>
            </form>

            <div class="text-center mt-3">
                <p class="text-sm text-light">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary font-bold">Masuk di sini! 🔑</a></p>
            </div>

            <div class="text-center mt-2">
                <a href="{{ route('home') }}" class="text-sm text-light">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>
