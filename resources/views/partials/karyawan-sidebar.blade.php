<aside class="gg-sidebar">
    <a href="{{ route('landing') }}" class="gg-brand mb-2">
        <span class="leaf"><img src="{{ asset('img/logo.png') }}" alt="Logo Bank Sampah Go Green"></span> GoGreen Bank
    </a>

    <div class="gg-profile">
        @if (Auth::user()->avatar)
            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Foto profil">
        @else
            <div class="ph"><i class="bi bi-person-fill"></i></div>
        @endif
        <div>
            <div class="fw-bold small">{{ Auth::user()->name }}</div>
            <div class="text-muted-2" style="font-size:.72rem">Kontributor-Eco</div>
        </div>
    </div>

    <nav class="gg-menu">
        @php $from = request('from', 'scan'); @endphp
        <a href="{{ route('karyawan.dashboard') }}" class="{{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Ringkasan</a>
        <a href="{{ route('karyawan.scan') }}" class="{{ request()->routeIs('karyawan.scan') || (request()->routeIs('karyawan.input') && $from !== 'dropoff') ? 'active' : '' }}"><i class="bi bi-qr-code-scan"></i> Scan QR Code</a>
        <a href="{{ route('karyawan.dropoff') }}" class="{{ request()->routeIs('karyawan.dropoff') || (request()->routeIs('karyawan.input') && $from === 'dropoff') ? 'active' : '' }}"><i class="bi bi-geo-alt"></i> Drop Off</a>
    </nav>

    <div class="mt-auto gg-menu">
        <a href="{{ route('settings') }}" class="{{ request()->routeIs('settings') ? 'active' : '' }}"><i class="bi bi-gear"></i> Pengaturan</a>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="border-0 bg-transparent w-100 text-start" style="display:flex;align-items:center;gap:.7rem;padding:.7rem .9rem;color:var(--danger);font-weight:600;">
                <i class="bi bi-box-arrow-right"></i> Keluar
            </button>
        </form>
    </div>
</aside>
