<?php

namespace App\Http\Controllers;

use App\Models\WasteCategory;
use App\Models\DropOffReport;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NasabahController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $transactions = $user->transactions()->with('wasteCategory')->take(5)->get();
        $featured = WasteCategory::whereIn('name', ['PET Bersih', 'Kardus', 'Elektronik', 'Botol Kaca'])->get();
        return view('nasabah.dashboard', compact('user', 'transactions', 'featured'));
    }

    public function katalog()
    {
        $items = WasteCategory::orderBy('id')->get();
        return view('nasabah.katalog', compact('items'));
    }

    public function qr()
    {
        return view('nasabah.qr', ['user' => Auth::user()]);
    }

    public function dropOffForm()
    {
        return view('nasabah.dropoff');
    }

    public function storeDropOff(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:4096'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $request->file('photo')->store('dropoffs', 'public');

        DropOffReport::create([
            'user_id' => Auth::id(),
            'photo_path' => $path,
            'note' => $request->input('note'),
            'status' => 'menunggu',
        ]);

        return redirect()->route('nasabah.dashboard')
            ->with('success', 'Laporan drop-off berhasil dikirim. Menunggu verifikasi petugas.');
    }

    public function withdraw(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:1000'],
            'method' => ['required', 'in:bank,ewallet'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        if ($data['amount'] > $user->balance) {
            return back()->with('error', 'Nominal penarikan melebihi saldo tabungan Anda.');
        }

        Withdrawal::create($data + ['user_id' => $user->id, 'status' => 'menunggu']);
        $user->decrement('balance', $data['amount']);

        return redirect()->route('nasabah.dashboard')
            ->with('success', 'Permintaan penarikan saldo berhasil dikirim.');
    }
}
