@extends('layouts.dashboard')
@section('title', 'Cetak Kartu — GoGreen Bank')
@section('sidebar') @include('partials.karyawan-sidebar') @endsection

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('karyawan.card.index') }}" class="btn btn-sm btn-outline-forest"><i class="bi bi-arrow-left"></i> Kembali</a>
    <h4 class="fw-800 mb-0">Kartu Anggota — {{ $user->name }}</h4>
</div>

<div style="overflow:auto">
  <div id="card" style="position:relative;width:800px;margin:auto;font-family:sans-serif">
    <img src="{{ asset('img/kartu-template.png') }}" style="width:100%;display:block" alt="Template kartu">
    <div id="qrbox" style="position:absolute;left:9.5%;top:22%;line-height:0"></div>
    <div style="position:absolute;left:65.5%;top:39.5%;transform:translateY(-100%);font-size:22px;font-weight:800;color:#111">{{ $user->name }}</div>
    <div style="position:absolute;left:74%;top:50.5%;transform:translateY(-100%);font-size:22px;font-weight:800;color:#111">{{ $user->member_id }}</div>
    <div style="position:absolute;left:68%;top:61.5%;transform:translateY(-100%);font-size:22px;font-weight:800;color:#111">{{ str_replace('-', '', $user->phone) }}</div>
  </div>
</div>

<div class="text-center mt-4 d-flex gap-2 justify-content-center">
    <button id="btnDownload" class="btn btn-forest"><i class="bi bi-download"></i> Unduh PNG</button>
    <form method="POST" action="{{ route('karyawan.card.printed', $cardRequest) }}"
          data-confirm="Tandai kartu {{ $user->name }} sudah dicetak?"
          data-confirm-title="Selesai Cetak" data-confirm-ok="Ya, Selesai">
        @csrf
        <button class="btn btn-green">Tandai Sudah Dicetak</button>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    new QRCode(document.getElementById('qrbox'), {
        text: @json($user->member_id),
        width: 280,
        height: 280,
        correctLevel: QRCode.CorrectLevel.M
    });

    document.getElementById('btnDownload').addEventListener('click', function () {
        html2canvas(document.getElementById('card'), { scale: 2, backgroundColor: null, useCORS: true })
            .then(function (canvas) {
                var a = document.createElement('a');
                a.href = canvas.toDataURL('image/png');
                a.download = 'kartu-{{ $user->member_id }}.png';
                a.click();
            });
    });
</script>
@endpush
@endsection