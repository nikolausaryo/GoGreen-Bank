<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard — GoGreen Bank')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/gogreen.css') }}" rel="stylesheet">
</head>
<body>
<div class="gg-shell">
    @yield('sidebar')

    <main class="gg-main">
            <div class="gg-topbar">
                <button class="gg-burger" id="ggBurger" aria-label="Buka menu"><i class="bi bi-list"></i></button>
                <span class="fw-800" style="font-size:1.1rem">GoGreen Bank</span>
            </div>
        @if (session('success'))
            <div class="alert alert-success border-0">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger border-0">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger border-0">
                <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<div class="gg-backdrop" id="ggBackdrop"></div>
<script>
(function () {
    var b = document.getElementById('ggBurger');
    var bd = document.getElementById('ggBackdrop');
    if (b) b.addEventListener('click', function () { document.body.classList.toggle('sidebar-open'); });
    if (bd) bd.addEventListener('click', function () { document.body.classList.remove('sidebar-open'); });
})();
</script>

@include('partials.chatbot')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
