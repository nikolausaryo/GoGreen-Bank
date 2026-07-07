<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\WasteCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Akun Karyawan (petugas) =====
        $karyawan = User::updateOrCreate(
            ['email' => 'karyawan@gogreen.bank'],
            [
                'name' => 'Portal Keberlanjutan',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
                'member_id' => 'GGB-EMP-01',
            ]
        );

        // ===== Akun Nasabah demo =====
        $sarah = User::updateOrCreate(
            ['email' => 'sarah@gogreen.bank'],
            [
                'name' => 'Sarah Adams',
                'password' => Hash::make('password'),
                'role' => 'nasabah',
                'member_id' => 'GGB-9021',
                'phone' => '0812-3456-7890',
                'address' => 'Cupuwatu II, Purwomartani, Kalasan',
                'balance' => 1500000,
            ]
        );

        User::updateOrCreate(
            ['email' => 'james@gogreen.bank'],
            [
                'name' => 'James Kim',
                'password' => Hash::make('password'),
                'role' => 'nasabah',
                'member_id' => 'GGB-9022',
                'balance' => 320000,
            ]
        );

        // ===== Contoh setoran untuk Sarah =====
        $pet   = WasteCategory::where('name', 'PET Bersih')->first();
        $hvs   = WasteCategory::where('name', 'Kertas HVS')->first();
        $kardus= WasteCategory::where('name', 'Kardus')->first();

        if ($pet && $hvs && $kardus) {
            $samples = [
                ['cat' => $kardus, 'weight' => 12.5, 'method' => 'scan_qr', 'status' => 'terverifikasi'],
                ['cat' => $pet,    'weight' => 4.2,  'method' => 'scan_qr', 'status' => 'terverifikasi'],
                ['cat' => $hvs,    'weight' => 24.5, 'method' => 'drop_off', 'status' => 'diproses'],
            ];

            foreach ($samples as $s) {
                Transaction::create([
                    'user_id' => $sarah->id,
                    'operator_id' => $karyawan->id,
                    'waste_category_id' => $s['cat']->id,
                    'weight' => $s['weight'],
                    'amount' => (int) round($s['weight'] * $s['cat']->price),
                    'method' => $s['method'],
                    'status' => $s['status'],
                ]);
            }
        }

        // ===== Contoh penarikan =====
        Withdrawal::create([
            'user_id' => $sarah->id, 'amount' => 500000, 'method' => 'bank',
            'account_name' => 'Bank Mandiri', 'account_number' => '2345-1234-5678', 'status' => 'terverifikasi',
        ]);
        Withdrawal::create([
            'user_id' => $sarah->id, 'amount' => 200000, 'method' => 'ewallet',
            'account_name' => 'DANA', 'account_number' => '081255670902', 'status' => 'terverifikasi',
        ]);
    }
}
