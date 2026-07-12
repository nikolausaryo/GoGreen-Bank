@extends('layouts.dashboard')
@section('title', 'Ringkasan — GoGreen Bank')
@section('sidebar') @include('partials.karyawan-sidebar') @endsection

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <span class="badge-soft"><i class="bi bi-broadcast"></i> Green-Hub Alpha · Terhubung IoT</span>
</div>

{{-- Metrik --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3"><div class="metric-card h-100"><div class="l">Total Diproses</div><div class="v text-green">{{ $stats['berat_hari_ini'] }} <small class="fs-6 text-muted-2">kg</small></div><div class="small text-muted-2">Ditimbang hari ini</div></div></div>
    <div class="col-6 col-lg-3"><div class="metric-card h-100"><div class="l">Tanggal</div><div class="v text-green">{{ $stats['tanggal'] }}</div><div class="small text-muted-2">{{ $stats['hari'] }}</div></div></div>
    <div class="col-6 col-lg-3"><div class="metric-card h-100"><div class="l">Tugas Tertunda</div><div class="v">{{ $stats['pending'] }} <small class="fs-6 text-muted-2">Aktif</small></div><span class="badge-status badge-menunggu mt-1">Perlu Tindakan</span></div></div>
    <div class="col-6 col-lg-3"><div class="metric-card h-100"><div class="l">Nasabah Masuk</div><div class="v text-green">{{ $stats['nasabah_masuk'] }} <small class="fs-6 text-muted-2">Nasabah</small></div><div class="small text-muted-2">Scan QR / drop-off hari ini</div></div></div>
</div>

{{-- Aktivitas Terbaru --}}
<div class="gg-card p-4 mb-3">
    <h5 class="fw-800 mb-0">Informasi Aktivitas Terbaru</h5>
    <p class="text-muted-2 small">Ringkasan aktivitas konversi sampah menjadi sumber daya</p>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr class="text-muted-2 small"><th>ID Setoran</th><th>Kontributor</th><th>Jenis Sampah</th><th>Berat</th><th>Penghasilan</th><th>Status</th></tr></thead>
            <tbody>
            @forelse ($recentTransactions as $t)
                <tr>
                    <td class="fw-bold text-green">{{ $t->code }}</td>
                    <td>{{ $t->user->name }}</td>
                    <td><span class="badge-soft">{{ strtoupper($t->wasteCategory->name) }}</span></td>
                    <td>{{ rtrim(rtrim(number_format($t->weight,2),'0'),'.') }} {{ $t->wasteCategory->unit }}</td>
                    <td class="fw-800 text-green">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                    <td><span class="badge-status badge-{{ $t->status }}"><i class="bi bi-check-circle"></i> {{ ucfirst($t->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted-2 py-4">Belum ada aktivitas.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Penarikan Terbaru --}}
<div class="gg-card p-4">
    <h5 class="fw-800 mb-0">Informasi Penarikan Terbaru</h5>
    <p class="text-muted-2 small">Ringkasan aktivitas penarikan tabungan</p>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr class="text-muted-2 small"><th>ID Penarikan</th><th>Kontributor</th><th>Opsi Penarikan</th><th>Nomor Rekening/E-Wallet</th><th>Nominal</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse ($recentWithdrawals as $w)
                <tr>
                    <td class="fw-bold text-green">{{ $w->code }}</td>
                    <td>{{ $w->user->name }}</td>
                    <td><span class="badge-soft">{{ strtoupper($w->account_name) }}</span></td>
                    <td>{{ $w->account_number }}</td>
                    <td class="fw-800 text-green">Rp {{ number_format($w->amount, 0, ',', '.') }}</td>
                    <td><span class="badge-status badge-{{ $w->status }}"><i class="bi bi-check-circle"></i> {{ ucfirst($w->status) }}</span></td>
                    <td>
                        @if ($w->status === 'menunggu')
                            <form method="POST" action="{{ route('karyawan.withdrawal.verify', $w) }}"
                                data-confirm="Verifikasi penarikan {{ $w->code }}? Saldo nasabah akan dipotong Rp {{ number_format($w->amount, 0, ',', '.') }}."
                                data-confirm-title="Verifikasi Penarikan"
                                data-confirm-ok="Ya, Verifikasi">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-forest">Verifikasi</button>
                            </form>
                        @else
                            <span class="text-muted-2 small">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted-2 py-4">Belum ada penarikan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
