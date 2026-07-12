<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\WasteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    // Ringkasan / dashboard utama karyawan
    public function dashboard()
    {
        $recentTransactions = Transaction::with(['user', 'wasteCategory'])->latest()->take(5)->get();
        $recentWithdrawals = Withdrawal::with('user')->latest()->take(3)->get();

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

        return view('karyawan.dashboard', compact('recentTransactions', 'recentWithdrawals', 'stats'));
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

    public function markPrinted(\App\Models\CardRequest $cardRequest)
    {
        $cardRequest->update(['status' => 'dicetak']);
        return redirect()->route('karyawan.card.index')
            ->with('success', 'Kartu ' . $cardRequest->user->name . ' ditandai sudah dicetak.');
    }
}
