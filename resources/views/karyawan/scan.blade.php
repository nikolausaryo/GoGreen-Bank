@extends('layouts.dashboard')
@section('title', 'Scan QR Code — GoGreen Bank')
@section('sidebar') @include('partials.karyawan-sidebar') @endsection

@section('content')
<div class="text-center">
    <div class="fw-bold">Pindai Kode QR Nasabah</div>
    <p class="text-muted-2 small">Arahkan kamera ke kartu QR Nasabah untuk memulai transaksi setoran.</p>
</div>

<div class="mx-auto" style="max-width:560px">
    <div id="qr-reader" class="mx-auto mb-2" style="max-width:400px"></div>
    <p class="text-center text-muted-2 small mb-3">Izinkan akses kamera, lalu arahkan ke QR nasabah (kartu atau layar HP).</p>

    <div class="text-center text-muted-2 small mb-2">— ATAU MASUKKAN ID NASABAH SECARA MANUAL —</div>
    <form method="POST" action="{{ route('karyawan.scan') }}" id="scan-form">
        @csrf
        <input type="hidden" name="from" value="{{ request('from', 'scan') }}">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
            <input id="member_id" name="member_id" class="form-control" placeholder="Contoh: GGB-9021" required>
            <button class="btn btn-green-soft px-4"><i class="bi bi-search"></i> Cari</button>
        </div>
    </form>
    <a href="{{ route('karyawan.dashboard') }}" class="d-block text-center mt-3 fw-bold" style="color:var(--danger)"><i class="bi bi-x-circle"></i> Batal & Kembali</a>

    <div class="row g-2 mt-4">
        <div class="col-md-4"><div class="soft-card p-3"><i class="bi bi-geo-alt text-green"></i><div class="small text-muted-2">Lokasi Akurat</div><div class="fw-bold small">Bank Sampah Go Green</div></div></div>
        <div class="col-md-4"><div class="soft-card p-3"><i class="bi bi-camera-video text-green"></i><div class="small text-muted-2">Koneksi Kamera</div><div class="fw-bold small">Stabil (HD)</div></div></div>
        <div class="col-md-4"><div class="soft-card p-3"><i class="bi bi-shield-check text-green"></i><div class="small text-muted-2">Mode Keamanan</div><div class="fw-bold small">Enkripsi Aktif</div></div></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    function onScanSuccess(decodedText) {
        document.getElementById('member_id').value = decodedText.trim();
        try { html5QrcodeScanner.clear(); } catch (e) {}
        document.getElementById('scan-form').submit();
    }

    var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        rememberLastUsedCamera: true,
        supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
    }, false);
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endpush
@endsection
