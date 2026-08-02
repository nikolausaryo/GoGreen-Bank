<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\WasteCategory;
use App\Models\MonthlyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    // Ringkasan / dashboard utama karyawan
    public function dashboard(Request $request)
    {
        // Bulan yang sedang ditampilkan (default: bulan ini)
        try {
            $cursor = $request->query('month')
                ? \Carbon\Carbon::createFromFormat('Y-m', $request->query('month'))->startOfMonth()
                : \Carbon\Carbon::now()->startOfMonth();
        } catch (\Exception $e) {
            $cursor = \Carbon\Carbon::now()->startOfMonth();
        }
        $start = $cursor->copy()->startOfMonth();
        $end   = $cursor->copy()->endOfMonth();

        // Data disaring per bulan (data lama tetap tersimpan, hanya tampilannya difilter)
        $monthTransactions = Transaction::with(['user', 'wasteCategory'])
            ->whereBetween('created_at', [$start, $end])->latest()->get();
        $monthWithdrawals = Withdrawal::with('user')
            ->whereBetween('created_at', [$start, $end])->latest()->get();

        $period = [
            'label'      => $cursor->locale('id')->translatedFormat('F Y'),
            'prev'       => $cursor->copy()->subMonth()->format('Y-m'),
            'next'       => $cursor->copy()->addMonth()->format('Y-m'),
            'is_current' => $cursor->isSameMonth(\Carbon\Carbon::now()),
        ];

        $today = \Carbon\Carbon::today();

        $stats = [
            // Akumulasi berat yang ditimbang hari ini
            'berat_hari_ini' => round(Transaction::whereDate('created_at', $today)->sum('weight'), 2),

            // Tanggal & hari saat dashboard dibuka
            'tanggal' => \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y'),
            'hari'    => \Carbon\Carbon::now()->locale('id')->translatedFormat('l'),

            // Tugas tertunda (tetap seperti sebelumnya)
            'pending' => Transaction::where('status', 'menunggu')->count()
                        + \App\Models\DropOffReport::where('status', 'menunggu')->count(),

            // Jumlah nasabah berbeda yang masuk hari ini (scan QR atau drop-off)
            'nasabah_masuk' => Transaction::whereDate('created_at', $today)
                        ->whereIn('method', ['scan_qr', 'drop_off'])
                        ->distinct()->count('user_id'),
        ];

        return view('karyawan.dashboard', compact('monthTransactions', 'monthWithdrawals', 'stats', 'period'));
    }

    // Halaman scan QR / cari nasabah manual
    public function scan(Request $request)
    {
        $from = $request->input('from', 'scan');
        $nasabah = null;
        if ($request->filled('member_id')) {
            $nasabah = User::where('member_id', $request->member_id)->where('role', 'nasabah')->first();
            if ($nasabah) {
                return redirect()->route('karyawan.input', ['user' => $nasabah->id, 'from' => $from]);
            }
            return back()->with('error', 'Nasabah dengan ID tersebut tidak ditemukan.');
        }
        return view('karyawan.scan', compact('nasabah'));
    }

    // Daftar nasabah aktif untuk metode drop-off
    public function dropOff()
    {
        $nasabahs = User::where('role', 'nasabah')->withCount('transactions')->get();
        $reports = \App\Models\DropOffReport::with('user')->latest()->get();
        return view('karyawan.dropoff', compact('nasabahs', 'reports'));
    }

    // Halaman input penimbangan untuk 1 nasabah
    public function input(User $user)
    {
        abort_unless($user->isNasabah(), 404);
        $categories = WasteCategory::orderBy('id')->get();
        return view('karyawan.input', compact('user', 'categories'));
    }

    // Simpan setoran -> update saldo nasabah
    public function storeTransaction(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'method'  => ['required', 'in:scan_qr,drop_off'],
            'items'   => ['required', 'array', 'min:1'],
            'items.*.waste_category_id' => ['required', 'exists:waste_categories,id'],
            'items.*.weight' => ['required', 'numeric', 'min:0.01'],
        ]);

        $total = 0;

        // Simpan tiap jenis sampah yang ditampung di Ringkasan Setoran
        foreach ($data['items'] as $item) {
            $category = WasteCategory::findOrFail($item['waste_category_id']);
            $amount = (int) round($item['weight'] * $category->price);
            $total += $amount;

            Transaction::create([
                'user_id' => $data['user_id'],
                'operator_id' => Auth::id(),
                'waste_category_id' => $category->id,
                'weight' => $item['weight'],
                'amount' => $amount,
                'method' => $data['method'],
                'status' => 'terverifikasi',
            ]);
        }

        // Tambah saldo tabungan nasabah sekali, total dari semua item
        User::where('id', $data['user_id'])->increment('balance', $total);

        $from = $data['method'] === 'drop_off' ? 'dropoff' : 'scan';

        return redirect()->route('karyawan.input', ['user' => $data['user_id'], 'from' => $from])
            ->with('success', 'Setoran tersimpan. Saldo nasabah bertambah Rp ' . number_format($total, 0, ',', '.') . '.');
    }

    // Verifikasi penarikan: potong saldo nasabah lalu tandai terverifikasi
    public function verifyWithdrawal(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'menunggu') {
            return back()->with('error', 'Penarikan ini sudah diproses sebelumnya.');
        }

        $nasabah = $withdrawal->user;

        if ($withdrawal->amount > $nasabah->balance) {
            return back()->with('error', 'Saldo nasabah tidak cukup untuk penarikan ini.');
        }

        $nasabah->decrement('balance', $withdrawal->amount);
        $withdrawal->update(['status' => 'terverifikasi']);

        return back()->with('success', 'Penarikan ' . $withdrawal->code
            . ' diverifikasi. Saldo nasabah dipotong Rp '
            . number_format($withdrawal->amount, 0, ',', '.') . '.');
    }

    public function verifyDropOff(\App\Models\DropOffReport $report)
    {
        if ($report->status !== 'menunggu') {
            return back()->with('error', 'Laporan ini sudah diproses sebelumnya.');
        }
        $report->update(['status' => 'terverifikasi']);
        return back()->with('success', 'Laporan drop-off dari ' . $report->user->name . ' telah diverifikasi.');
    }

    public function cardRequests()
    {
        $requests = \App\Models\CardRequest::with('user')->where('status', 'menunggu')->latest()->get();
        return view('karyawan.cards', compact('requests'));
    }

    public function showCard(\App\Models\CardRequest $cardRequest)
    {
        $user = $cardRequest->user;
        return view('karyawan.card-print', compact('cardRequest', 'user'));
    }

    /* ============ Kelola Jenis Sampah & Harga (CRUD) ============ */

    // Daftar semua jenis sampah
    public function categories()
    {
        $categories = WasteCategory::orderBy('category')->orderBy('name')->get();
        return view('karyawan.categories.index', compact('categories'));
    }

    // Form tambah
    public function createCategory()
    {
        return view('karyawan.categories.form', ['category' => new WasteCategory()]);
    }

    // Simpan data baru
    public function storeCategory(Request $request)
    {
        $data = $this->validateCategory($request);
        WasteCategory::create($data);

        return redirect()->route('karyawan.categories.index')
            ->with('success', 'Jenis sampah "' . $data['name'] . '" berhasil ditambahkan.');
    }

    // Form ubah
    public function editCategory(WasteCategory $category)
    {
        return view('karyawan.categories.form', compact('category'));
    }

    // Perbarui data
    public function updateCategory(Request $request, WasteCategory $category)
    {
        $data = $this->validateCategory($request);
        $category->update($data);

        return redirect()->route('karyawan.categories.index')
            ->with('success', 'Jenis sampah "' . $data['name'] . '" berhasil diperbarui.');
    }

    // Hapus data (dicegah bila sudah dipakai transaksi)
    public function destroyCategory(WasteCategory $category)
    {
        if ($category->transactions()->exists()) {
            return back()->with('error', 'Jenis sampah "' . $category->name
                . '" tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
        }

        $name = $category->name;
        $category->delete();

        return back()->with('success', 'Jenis sampah "' . $name . '" berhasil dihapus.');
    }

    /* ============ Rekap / Laporan Bulanan ============ */

    // Rentang awal-akhir bulan dari periode 'YYYY-MM'
    private function monthRange(string $period): array
    {
        $cursor = \Carbon\Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        return [$cursor->copy()->startOfMonth(), $cursor->copy()->endOfMonth()];
    }

    // Daftar rekap tersimpan
    public function reports()
    {
        $reports = MonthlyReport::orderByDesc('period')->get();
        return view('karyawan.reports.index', [
            'reports'      => $reports,
            'defaultMonth' => \Carbon\Carbon::now()->format('Y-m'),
        ]);
    }

    // Buat / perbarui rekap untuk satu bulan
    public function storeReport(Request $request)
    {
        $data = $request->validate([
            'period' => ['required', 'date_format:Y-m'],
        ], [], ['period' => 'bulan']);

        $period = $data['period'];
        [$start, $end] = $this->monthRange($period);

        $totals = [
            'label'             => \Carbon\Carbon::createFromFormat('Y-m', $period)->locale('id')->translatedFormat('F Y'),
            'deposits_count'    => Transaction::whereBetween('created_at', [$start, $end])->count(),
            'total_weight'      => round(Transaction::whereBetween('created_at', [$start, $end])->sum('weight'), 2),
            'total_income'      => Transaction::whereBetween('created_at', [$start, $end])->sum('amount'),
            'withdrawals_count' => Withdrawal::whereBetween('created_at', [$start, $end])->count(),
            'total_withdrawal'  => Withdrawal::whereBetween('created_at', [$start, $end])->sum('amount'),
            'active_members'    => Transaction::whereBetween('created_at', [$start, $end])->distinct()->count('user_id'),
            'generated_by'      => Auth::id(),
        ];

        $report = MonthlyReport::updateOrCreate(['period' => $period], $totals);

        return redirect()->route('karyawan.reports.index')
            ->with('success', 'Rekap ' . $report->label . ' berhasil dibuat/diperbarui.');
    }

    // Hapus catatan rekap (data transaksi tidak terpengaruh)
    public function destroyReport(MonthlyReport $report)
    {
        $label = $report->label;
        $report->delete();

        return back()->with('success', 'Rekap ' . $label . ' berhasil dihapus. Data transaksi tetap aman.');
    }

    // Unduh rekap sebagai file Excel (.xlsx) — dibuat ulang dari data bulan tsb.
    public function downloadReport(MonthlyReport $report)
    {
        [$start, $end] = $this->monthRange($report->period);

        $transactions = Transaction::with(['user', 'wasteCategory'])
            ->whereBetween('created_at', [$start, $end])->orderBy('created_at')->get();
        $withdrawals = Withdrawal::with('user')
            ->whereBetween('created_at', [$start, $end])->orderBy('created_at')->get();

        $ss = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $ss->getActiveSheet();
        $sheet->setTitle('Rekap');

        $rupiah = fn ($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');

        // Judul
        $sheet->setCellValue('A1', 'REKAP BULANAN — GoGreen Bank');
        $sheet->setCellValue('A2', 'Periode: ' . $report->label);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true);

        // Ringkasan
        $sheet->setCellValue('A4', 'RINGKASAN');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $ringkasan = [
            ['Jumlah Setoran', $report->deposits_count . ' transaksi'],
            ['Total Berat Sampah', rtrim(rtrim(number_format($report->total_weight, 2, ',', '.'), '0'), ',') . ' kg'],
            ['Total Penghasilan Nasabah', $rupiah($report->total_income)],
            ['Jumlah Penarikan', $report->withdrawals_count . ' pengajuan'],
            ['Total Nominal Penarikan', $rupiah($report->total_withdrawal)],
            ['Nasabah Aktif', $report->active_members . ' nasabah'],
        ];
        $r = 5;
        foreach ($ringkasan as $row) {
            $sheet->setCellValue('A' . $r, $row[0]);
            $sheet->setCellValue('B' . $r, $row[1]);
            $r++;
        }

        // Daftar Setoran
        $r += 1;
        $sheet->setCellValue('A' . $r, 'DAFTAR SETORAN');
        $sheet->getStyle('A' . $r)->getFont()->setBold(true);
        $r++;
        $head = ['No', 'ID Setoran', 'Tanggal', 'Nasabah', 'Jenis Sampah', 'Berat', 'Penghasilan', 'Status'];
        $sheet->fromArray($head, null, 'A' . $r);
        $sheet->getStyle('A' . $r . ':H' . $r)->getFont()->setBold(true);
        $r++;
        $no = 1;
        foreach ($transactions as $t) {
            $sheet->fromArray([
                $no++,
                $t->code,
                $t->created_at->format('d/m/Y'),
                optional($t->user)->name,
                optional($t->wasteCategory)->name,
                rtrim(rtrim(number_format($t->weight, 2, ',', '.'), '0'), ',') . ' ' . optional($t->wasteCategory)->unit,
                $rupiah($t->amount),
                ucfirst($t->status),
            ], null, 'A' . $r);
            $r++;
        }
        if ($transactions->isEmpty()) {
            $sheet->setCellValue('A' . $r, 'Tidak ada setoran pada periode ini.');
            $r++;
        }

        // Daftar Penarikan
        $r += 1;
        $sheet->setCellValue('A' . $r, 'DAFTAR PENARIKAN');
        $sheet->getStyle('A' . $r)->getFont()->setBold(true);
        $r++;
        $head2 = ['No', 'ID Penarikan', 'Tgl Pengajuan', 'Nasabah', 'Opsi', 'Nomor Rekening/E-Wallet', 'Nominal', 'Status'];
        $sheet->fromArray($head2, null, 'A' . $r);
        $sheet->getStyle('A' . $r . ':H' . $r)->getFont()->setBold(true);
        $r++;
        $no = 1;
        foreach ($withdrawals as $w) {
            $sheet->fromArray([
                $no++,
                $w->code,
                $w->created_at->format('d/m/Y'),
                optional($w->user)->name,
                $w->account_name,
                ' ' . $w->account_number, // spasi depan agar tidak jadi angka
                $rupiah($w->amount),
                ucfirst($w->status),
            ], null, 'A' . $r);
            $r++;
        }
        if ($withdrawals->isEmpty()) {
            $sheet->setCellValue('A' . $r, 'Tidak ada penarikan pada periode ini.');
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($ss);
        $filename = 'Rekap_' . str_replace(' ', '_', $report->label) . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // Aturan validasi bersama (tambah & ubah)
    private function validateCategory(Request $request): array
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'price'    => ['required', 'integer', 'min:0'],
            'unit'     => ['required', 'string', 'max:20'],
            'icon'     => ['nullable', 'string', 'max:50'],
        ], [], [
            'name'     => 'nama jenis sampah',
            'category' => 'kategori',
            'price'    => 'harga',
            'unit'     => 'satuan',
        ]);

        // Pakai ikon default bila dikosongkan
        $data['icon'] = ($data['icon'] ?? '') ?: 'bi-recycle';

        return $data;
    }

    public function markPrinted(\App\Models\CardRequest $cardRequest)
    {
        $cardRequest->update(['status' => 'dicetak']);
        return redirect()->route('karyawan.card.index')
            ->with('success', 'Kartu ' . $cardRequest->user->name . ' ditandai sudah dicetak.');
    }
}
