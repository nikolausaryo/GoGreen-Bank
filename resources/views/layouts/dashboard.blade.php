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
{{-- Modal konfirmasi serbaguna (dipakai semua form ber-atribut data-confirm) --}}
<style>
#ggConfirmModal .modal-content{ border:0; border-radius:1rem; box-shadow:0 18px 45px rgba(0,0,0,.15); }
#ggConfirmModal .modal-header{ border-bottom:0; padding:1.25rem 1.25rem .25rem; }
#ggConfirmModal .modal-title{ font-size:1.15rem; }
#ggConfirmModal .modal-body{ font-size:1rem; line-height:1.65; color:#374151; padding:.5rem 1.25rem 1rem; }
#ggConfirmModal .modal-footer{ border-top:0; padding:.5rem 1.25rem 1.25rem; }
#ggConfirmModal .btn-forest{ padding-left:1.25rem; padding-right:1.25rem; }
</style>
<div class="modal fade" id="ggConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="ggConfirmTitle">Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body" id="ggConfirmBody">Apakah Anda yakin?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-forest" id="ggConfirmOk">Ya, Lanjut</button>
      </div>
    </div>
  </div>
</div>
<script>
(function () {
    var modalEl = document.getElementById('ggConfirmModal');
    if (!modalEl || typeof bootstrap === 'undefined') return;
    var modal   = new bootstrap.Modal(modalEl);
    var bodyEl  = document.getElementById('ggConfirmBody');
    var titleEl = document.getElementById('ggConfirmTitle');
    var okBtn   = document.getElementById('ggConfirmOk');
    var pendingForm = null;

    function escapeHtml(s){
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (form.matches && form.matches('[data-confirm]')) {
            e.preventDefault();
            pendingForm = form;

            // amankan teks dulu, lalu tonjolkan nominal "Rp ..."
            var pesan = escapeHtml(form.getAttribute('data-confirm'));
            pesan = pesan.replace(/Rp\s?[\d.,]*\d/g, '<span class="text-green fw-bold">$&</span>');
            bodyEl.innerHTML = pesan;

            titleEl.textContent = form.getAttribute('data-confirm-title') || 'Konfirmasi';
            okBtn.textContent   = form.getAttribute('data-confirm-ok') || 'Ya, Lanjut';
            modal.show();
        }
    });

    okBtn.addEventListener('click', function () {
        if (pendingForm) {
            var f = pendingForm;
            pendingForm = null;
            modal.hide();
            f.submit();
        }
    });

    modalEl.addEventListener('hidden.bs.modal', function () { pendingForm = null; });
})();
</script>
@stack('scripts')
</body>
</html>
