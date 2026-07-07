<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Sandi — GoGreen Bank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/gogreen.css') }}" rel="stylesheet">
</head>
<body>
<div class="auth-wrap">
    <div class="container py-4">
        <a href="{{ route('landing') }}" class="gg-brand"><span class="leaf"><img src="{{ asset('img/logo.png') }}" alt="Logo Bank Sampah Go Green" style="width:100%;height:100%;object-fit:cover;display:block;border-radius:8px"></span> GoGreen Bank</a>
    </div>

    <div class="container flex-grow-1 d-flex align-items-center justify-content-center pb-5">
        <div class="auth-card" style="width:100%;max-width:420px">
            <div class="text-center mb-3">
                <span class="leaf d-inline-grid mb-2" style="width:48px;height:48px;background:var(--green-bright);border-radius:14px;place-items:center;color:#fff;font-size:1.4rem"><i class="bi bi-key"></i></span>
                <h4 class="fw-800 mb-1">Lupa Kata Sandi</h4>
                <p class="text-muted-2 small">Masukkan email akun Anda, kami akan mengirimkan instruksi untuk mereset kata sandi.</p>
            </div>

            @if (session('status'))
            <div class="alert alert-success py-2 small">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <label class="form-label fw-semibold small">Email</label>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input name="email" type="email" value="{{ old('email') }}" class="form-control" placeholder="email@gogreen.bank" required>
                </div>
                @error('email')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                <button class="btn btn-forest w-100 py-2">Kirim Instruksi Reset</button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="small text-green fw-semibold"><i class="bi bi-arrow-left"></i> Kembali ke Masuk</a>
            </div>
        </div>
    </div>

    <footer class="gg-footer py-3">
        <div class="container small d-flex flex-wrap justify-content-between">
            <span class="brand">GoGreen Bank</span>
            <span>© 2024 Platform Limbah-ke-Sumber Daya GoGreen.</span>
        </div>
    </footer>
</div>

@include('partials.chatbot')
</body>
</html>