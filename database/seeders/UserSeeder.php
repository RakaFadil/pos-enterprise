<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Suntik Akun Administrator
        User::create([
            'name' => 'Superadmin',
            'email' => 'super@mail.com',
            'password' => Hash::make('superadmin123'),
            'role' => 'admin'
        ]);

        // 2. Suntik Akun Karyawan Kasir
        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir1@mail.com',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir'
        ]);
    }
}
