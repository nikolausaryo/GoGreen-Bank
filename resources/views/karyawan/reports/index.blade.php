@extends('layouts.dashboard')
@section('title', 'Rekap Bulanan — GoGreen Bank')
@section('sidebar') @include('partials.karyawan-sidebar') @endsection

@section('content')
<h2 class="fw-800 mb-0">Rekap Bulanan</h2>
<p class="text-muted-2">Buat rekap data setoran &amp; penarikan per bulan, lalu unduh sebagai file Excel. Menghapus rekap tidak menghapus data transaksi aslinya.</p>

{{-- Buat rekap --}}
<div class="gg-card p-4 mb-3">
    <form method="POST" action="{{ route('karyawan.reports.store') }}"
          class="d-flex flex-wrap align-items-end gap-3">
        @csrf
        <div>
            <label class="form-label fw-bold mb-1">Pilih Bulan</label>
            <input type="month" name="period" value="{{ old('period', $defaultMonth) }}"
                   max="{{ $defaultMonth }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-forest">
            <i class="bi bi-plus-lg me-1"></i> Buat Rekap
        </button>
        <div class="text-muted-2 small ms-auto" style="max-width:320px">
            Membuat rekap bulan yang sama akan memperbarui angkanya sesuai data terkini.
        </div>
    </form>
</div>

{{-- Daftar rekap --}}
<div class="gg-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr class="text-muted-2" style="font-size:.82rem">
                    <th class="ps-4 py-3">Periode</th>
                    <th class="py-3 text-center">Setoran</th>
                    <th class="py-3 text-end">Total Berat</th>
                    <th class="py-3 text-end">Penghasilan</th>
                    <th class="py-3 text-center">Penarikan</th>
                    <th class="py-3 text-end">Total Penarikan</th>
                    <th class="py-3 text-center">Nasabah</th>
                    <th class="py-3 text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reports as $rep)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="fw-bold">{{ $rep->label }}</div>
                        <div class="text-muted-2 small">Dibuat {{ $rep->created_at->locale('id')->translatedFormat('d M Y') }}</div>
                    </td>
                    <td class="py-3 text-center">{{ $rep->deposits_count }}</td>
                    <td class="py-3 text-end">{{ rtrim(rtrim(number_format($rep->total_weight, 2, ',', '.'), '0'), ',') }} kg</td>
                    <td class="py-3 text-end text-green fw-800">Rp {{ number_format($rep->total_income, 0, ',', '.') }}</td>
                    <td class="py-3 text-center">{{ $rep->withdrawals_count }}</td>
                    <td class="py-3 text-end">Rp {{ number_format($rep->total_withdrawal, 0, ',', '.') }}</td>
                    <td class="py-3 text-center">{{ $rep->active_members }}</td>
                    <td class="py-3 text-end pe-4 text-nowrap">
                        <a href="{{ route('karyawan.reports.download', $rep) }}" class="btn btn-sm btn-forest" title="Unduh Excel">
                            <i class="bi bi-download"></i> Excel
                        </a>
                        <form method="POST" action="{{ route('karyawan.reports.destroy', $rep) }}"
                              class="d-inline"
                              data-confirm="Hapus rekap {{ $rep->label }}? Hanya catatan rekap yang dihapus, data transaksi tetap aman."
                              data-confirm-title="Hapus Rekap"
                              data-confirm-ok="Ya, Hapus">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted-2 py-5">
                        <i class="bi bi-file-earmark-spreadsheet d-block mb-2" style="font-size:1.8rem"></i>
                        Belum ada rekap. Pilih bulan di atas lalu klik <span class="fw-bold">Buat Rekap</span>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
