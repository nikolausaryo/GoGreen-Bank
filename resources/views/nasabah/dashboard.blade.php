@extends('layouts.dashboard')
@section('title', 'Dashboard Nasabah — GoGreen Bank')
@section('sidebar') @include('partials.nasabah-sidebar') @endsection

@section('content')
@if (!$cardRequest)
    <div class="gg-card p-3 mb-3 d-flex align-items-center gap-3" style="border-left:4px solid var(--green)">
        <i class="bi bi-person-vcard fs-3 text-green"></i>
        <div class="flex-grow-1">
            <div class="fw-800">Kartu Anggota Anda belum dicetak</div>
            <div class="text-muted-2 small">Ajukan cetak kartu, lalu ambil ke petugas.</div>
        </div>
        <form method="POST" action="{{ route('nasabah.card.request') }}">
            @csrf
            <button class="btn btn-forest btn-sm">Ajukan Kartu</button>
        </form>
    </div>
@elseif ($cardRequest->status === 'menunggu')
    <div class="gg-card p-3 mb-3 d-flex align-items-center gap-3" style="border-left:4px solid #f0ad4e">
        <i class="bi bi-hourglass-split fs-3 text-green"></i>
        <div>
            <div class="fw-800">Pengajuan kartu sedang diproses</div>
            <div class="text-muted-2 small">Petugas akan menyiapkan kartu Anda.</div>
        </div>
    </div>
@endif

<h2 class="fw-800 mb-0">Dashboard Nasabah</h2>
<p class="text-muted-2">Pantau kontribusi hijau dan hadiah Anda secara real-time.</p>

<div class="row g-3">
    {{-- Saldo --}}
    <div class="col-lg-4">
        <div class="saldo-card h-100 d-flex flex-column">
            <div class="small" style="color:#9fdfae">Tabungan saat ini</div>
            <div class="small mb-2" style="color:#9fdfae">Dapat ditukarkan dengan uang tunai.</div>
            <div class="amount">{{ number_format(Auth::user()->balance, 0, ',', '.') }}</div>
            <button class="btn btn-green mt-auto w-100 py-2" data-bs-toggle="modal" data-bs-target="#withdrawModal">Ambil</button>
        </div>
    </div>

    {{-- Katalog terkini --}}
    <div class="col-lg-8">
        <div class="gg-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-800 mb-0">Katalog Harga Terkini</h5>
                <a href="{{ route('nasabah.katalog') }}" class="text-green fw-bold small">Lihat Semua Item</a>
            </div>
            <div class="row g-2">
                @foreach ($featured as $item)
                <div class="col-md-6">
                    <div class="price-row">
                        <div class="price-icon"><i class="bi {{ $item->icon }}"></i></div>
                        <div class="fw-bold">{{ $item->name }}</div>
                        <div class="ms-auto text-green fw-800">Rp {{ number_format($item->price, 0, ',', '.') }}<span class="text-muted-2 fw-normal small">/{{ $item->unit }}</span></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Riwayat --}}
<div class="gg-card p-4 mt-3">
    <h5 class="fw-800 mb-3">Detail Riwayat Transaksi</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr class="text-muted-2 small">
                <th>Tanggal & ID</th><th>Jenis Sampah</th><th>Metode</th><th>Jumlah</th><th>Penghasilan</th><th>Status</th>
            </tr></thead>
            <tbody>
            @forelse ($transactions as $t)
                <tr>
                    <td><div class="fw-bold">{{ $t->created_at->format('d M Y') }}</div><div class="text-muted-2 small">{{ $t->code }}</div></td>
                    <td><i class="bi {{ $t->wasteCategory->icon }} text-green"></i> {{ $t->wasteCategory->name }}</td>
                    <td class="small">{{ $t->method === 'scan_qr' ? 'Scan Kode QR' : 'Drop Off' }}</td>
                    <td>{{ rtrim(rtrim(number_format($t->weight,2),'0'),'.') }} {{ $t->wasteCategory->unit }}</td>
                    <td class="fw-800 text-green">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                    <td><span class="badge-status badge-{{ $t->status }}">{{ ucfirst($t->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted-2 py-4">Belum ada transaksi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Akses cepat setor --}}
<div class="row g-3 mt-1">
    <div class="col-md-6">
        <div class="soft-card p-4 text-center h-100">
            <div class="price-icon mx-auto mb-2 bg-white"><i class="bi bi-qr-code text-green"></i></div>
            <div class="fw-bold fs-5">Drop Langsung</div>
            <p class="text-muted-2 small">Verifikasi setoran Anda dengan staf menggunakan ID unik Anda dengan cepat.</p>
            <a href="{{ route('nasabah.qr') }}" class="btn btn-outline-forest w-100">Tampilkan Kode QR</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="soft-card p-4 text-center h-100">
            <div class="price-icon mx-auto mb-2 bg-white"><i class="bi bi-robot text-green"></i></div>
            <div class="fw-bold fs-5">Drop-off</div>
            <p class="text-muted-2 small">Drop-off di smart bin otomatis? Lapor di sini untuk memastikan poin Anda tercatat.</p>
            <a href="{{ route('nasabah.dropoff') }}" class="btn btn-outline-forest w-100">Upload Foto</a>
        </div>
    </div>
</div>

{{-- Modal Ambil Tabungan --}}
<div class="modal fade" id="withdrawModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:20px">
      <div class="modal-body p-4">
        <div class="text-center mb-3">
            <span class="leaf d-inline-grid mb-2" style="width:48px;height:48px;background:var(--green-bright);border-radius:14px;place-items:center;color:#fff;font-size:1.3rem"><i class="bi bi-recycle"></i></span>
            <h5 class="fw-800 mb-1">Ambil Tabungan?</h5>
            <p class="text-muted-2 small">Silahkan memilih opsi penarikan dan nominal uang yang akan ditarik dari tabungan anda</p>
        </div>
        <form method="POST" action="{{ route('nasabah.withdraw') }}">
            @csrf
            <div class="text-center fw-bold small mb-1">Masukan Nominal</div>
            <input type="number" name="amount" class="form-control mb-3 soft-card border-0" placeholder="Masukan Jumlah Nominal Uang">

            <div class="text-center fw-bold small mb-2">Pilih Opsi Penarikan</div>
            <div class="fw-semibold small mb-1">Transfer Bank / E-Wallet</div>
            <select name="method" class="form-select mb-2 soft-card border-0">
                <option value="bank">Transfer Bank</option>
                <option value="ewallet">E-Wallet</option>
            </select>
            <input name="account_name" class="form-control mb-2 soft-card border-0" placeholder="Masukan Nama Bank / E-Wallet">
            <input name="account_number" class="form-control mb-3 soft-card border-0" placeholder="Masukan Nomor Rekening / E-Wallet">

            <button class="btn btn-forest w-100 mb-2 py-2">Kirim</button>
            <button type="button" class="btn w-100 py-2 fw-bold text-white" style="background:var(--danger)" data-bs-dismiss="modal">Batal</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
