@extends('layouts.dashboard')
@section('title', 'Lapor Drop Off — GoGreen Bank')
@section('sidebar') @include('partials.nasabah-sidebar') @endsection

@section('content')
<h2 class="fw-800 mb-0">Lapor Drop Off</h2>
<p class="text-muted-2">Jangan lupa bukti foto dan kode nasabahnya ya.</p>

<div class="gg-card p-5">
    <div class="text-center">
        <div class="fw-bold">Foto sampah anda</div>
        <p class="text-muted-2 small">Arahkan kamera ke sampah yang akan disetorkan.</p>
    </div>

    <form method="POST" action="{{ route('nasabah.dropoff.store') }}" enctype="multipart/form-data" class="mx-auto" style="max-width:520px">
        @csrf

        {{-- Ketuk kotak ini untuk ambil foto / pilih dari galeri, lalu tampil pratinjaunya --}}
        <label for="photoInput" class="soft-card d-flex flex-column align-items-center justify-content-center mb-3 position-relative overflow-hidden"
               style="height:260px;background:var(--inverse);cursor:pointer">
            <div id="photoPlaceholder" class="d-flex flex-column align-items-center text-center px-3">
                <i class="bi bi-camera text-white fs-1 opacity-50"></i>
                <span class="text-white-50 small mt-2">Ketuk untuk ambil foto / pilih dari galeri</span>
            </div>
            <img id="photoPreview" alt="Pratinjau foto" style="display:none;position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
        </label>

        <input type="file" id="photoInput" name="photo" accept="image/*" class="form-control mb-3" required>
        <input name="note" class="form-control mb-3" placeholder="Keterangan (opsional)">

        <button class="btn btn-green-soft w-100 py-2"><i class="bi bi-send"></i> Kirim Laporan</button>
        <a href="{{ route('nasabah.dashboard') }}" class="d-block text-center mt-3 fw-bold" style="color:var(--danger)"><i class="bi bi-x-circle"></i> Batal & Kembali</a>
    </form>
</div>

@push('scripts')
<script>
    (function () {
        var input = document.getElementById('photoInput');
        var preview = document.getElementById('photoPreview');
        var placeholder = document.getElementById('photoPlaceholder');
        if (!input) return;
        input.addEventListener('change', function () {
            var file = input.files && input.files[0];
            if (!file) return;
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        });
    })();
</script>
@endpush

@endsection
