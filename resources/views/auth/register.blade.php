<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Akun — GoGreen Bank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/gogreen.css') }}" rel="stylesheet">
</head>
<body style="background:var(--surface)">
<div class="container py-3">
    <a href="{{ route('landing') }}" class="gg-brand"><span class="leaf"><img src="{{ asset('img/logo.png') }}" alt="Logo Bank Sampah Go Green"></span> GoGreen Bank</a>
</div>

<div class="container py-4">
    <div class="row g-5 align-items-center">
        {{-- Kiri: kampanye --}}
        <div class="col-lg-5">
            <span class="badge-soft mb-3 d-inline-flex align-items-center gap-2"><i class="bi bi-recycle"></i> Kontributor Eco Tingkat 1</span>
            <h1 class="display-hero text-green mb-3">Ubah Sampah Anda Menjadi Berkah.</h1>
            <p class="text-muted-2">Bergabunglah dengan GoGreen Bank hari ini. Setiap material yang didaur ulang adalah setoran menuju planet yang lebih bersih dan imbalan finansial eksklusif.</p>
            <div class="row g-3 mt-2">
                <div class="col-6"><div class="soft-card p-3 h-100"><i class="bi bi-recycle text-green fs-4"></i><div class="fw-bold mt-2">Setoran Mudah</div></div></div>
                <div class="col-6"><div class="gg-card p-3 h-100"><i class="bi bi-cash-stack text-green fs-4"></i><div class="fw-bold mt-2">Harga Bersaing</div></div></div>
            </div>
        </div>

        {{-- Kanan: form --}}
        <div class="col-lg-7">
            <div class="gg-card p-4 p-md-5">
                <h3 class="fw-800 mb-4">Pendaftaran Akun</h3>
                @if ($errors->any())
                    <div class="alert alert-danger border-0"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                @endif
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <label class="form-label fw-semibold small">Nama</label>
                    <div class="input-group mb-3"><span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input name="name" value="{{ old('name') }}" class="form-control" placeholder="Masukkan nama lengkap Anda"></div>

                    <label class="form-label fw-semibold small">Email</label>
                    <div class="input-group mb-3"><span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input name="email" value="{{ old('email') }}" class="form-control" placeholder="email@gogreen.bank"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nomor Handphone</label>
                            <div class="input-group mb-3"><span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input name="phone" value="{{ old('phone') }}" class="form-control" placeholder="+62 812-0000-0000"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Kata Sandi</label>
                            <div class="input-group mb-3"><span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••"></div>
                        </div>
                    </div>

                    <label class="form-label fw-semibold small">Alamat</label>
                    <div class="input-group mb-4"><span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <input name="address" value="{{ old('address') }}" class="form-control" placeholder="Alamat rumah atau kantor"></div>

                    <a href="{{ route('login') }}" class="btn soft-card w-100 mb-2 py-2 fw-semibold">Sudah punya akun? Masuk</a>
                    <button class="btn btn-forest w-100 py-2">Daftar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@include('partials.chatbot')
</body>
</html>
