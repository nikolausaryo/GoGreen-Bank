<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — GoGreen Bank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/gogreen.css') }}" rel="stylesheet">
</head>
<body>
<div class="auth-wrap">
    <div class="container py-4">
        <a href="{{ route('landing') }}" class="gg-brand"><span class="leaf"><img src="{{ asset('img/logo.png') }}" alt="Logo Bank Sampah Go Green"></span> GoGreen Bank</a>
    </div>

    <div class="container flex-grow-1 d-flex align-items-center justify-content-center pb-5">
        <div class="auth-card" style="width:100%;max-width:420px">
            <div class="text-center mb-3">
                <span class="leaf d-inline-grid mb-2" style="width:48px;height:48px;background:var(--green-bright);border-radius:14px;place-items:center;color:#fff;font-size:1.4rem"><i class="bi bi-recycle"></i></span>
                <h4 class="fw-800 mb-1">Selamat Datang Kembali</h4>
                <p class="text-muted-2 small">Akses dashboard kekayaan berkelanjutan Anda</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <input type="hidden" name="role" id="roleInput" value="{{ old('role', $role) }}">

                <div class="role-toggle mb-3">
                    <button type="button" data-role="nasabah" class="role-btn {{ old('role', $role) === 'nasabah' ? 'active' : '' }}">Nasabah</button>
                    <button type="button" data-role="karyawan" class="role-btn {{ old('role', $role) === 'karyawan' ? 'active' : '' }}">Karyawan</button>
                </div>

                <label class="form-label fw-semibold small">Nama Pengguna atau Email</label>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input name="email" value="{{ old('email') }}" class="form-control" placeholder="Masukkan nama pengguna Anda">
                </div>

                <div class="d-flex justify-content-between">
                    <label class="form-label fw-semibold small">Kata Sandi</label>
                    <a href="{{ route('password.request') }}" class="small text-green fw-semibold">Lupa Kata Sandi?</a>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" id="pwd" placeholder="••••••••">
                    <span class="input-group-text" role="button" onclick="const p=document.getElementById('pwd');p.type=p.type==='password'?'text':'password'"><i class="bi bi-eye"></i></span>
                </div>

                <div id="createAccountBox" class="border rounded-3 p-2 text-center small mb-3" @if (old('role', $role) === 'karyawan') style="display:none" @endif>Baru di sini? <a href="{{ route('register') }}" class="text-green fw-bold">Buat Akun</a></div>

                <button class="btn btn-forest w-100 py-2">Masuk ke Portal <i class="bi bi-arrow-right ms-1"></i></button>
            </form>

            <p class="text-center text-muted-2 small mt-3 mb-0">
                Demo nasabah: <b>sarah@gogreen.bank</b> · karyawan: <b>karyawan@gogreen.bank</b><br>kata sandi: <b>password</b>
            </p>
        </div>
    </div>

    <footer class="gg-footer py-3">
        <div class="container small d-flex flex-wrap justify-content-between">
            <span class="brand">GoGreen Bank</span>
            <span>© 2024 Platform Limbah-ke-Sumber Daya GoGreen.</span>
        </div>
    </footer>
</div>

<script>
    const createBox = document.getElementById('createAccountBox');
    document.querySelectorAll('.role-btn').forEach(b => b.addEventListener('click', () => {
        document.querySelectorAll('.role-btn').forEach(x => x.classList.remove('active'));
        b.classList.add('active');
        document.getElementById('roleInput').value = b.dataset.role;
        // "Buat Akun" hanya untuk nasabah
        if (createBox) createBox.style.display = b.dataset.role === 'karyawan' ? 'none' : '';
    }));
</script>
@include('partials.chatbot')
</body>
</html>
