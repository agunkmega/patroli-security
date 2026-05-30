<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\Guard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@patroli.com',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin,
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $supervisor = User::create([
            'name' => 'Supervisor Utama',
            'email' => 'supervisor@patroli.com',
            'password' => Hash::make('password'),
            'role' => UserRole::Supervisor,
            'phone' => '081234567891',
            'is_active' => true,
        ]);

        $guardUsers = [];
        $guardNames = [
            ['name' => 'Ahmad Fauzi', 'email' => 'guard@patroli.com', 'number' => 'GRD-001', 'nik' => '3578010101900001'],
            ['name' => 'Bambang Susilo', 'email' => 'bambang@patroli.com', 'number' => 'GRD-002', 'nik' => '3578010101900002'],
            ['name' => 'Citra Dewi', 'email' => 'citra@patroli.com', 'number' => 'GRD-003', 'nik' => '3578010101900003'],
            ['name' => 'Denny Pratama', 'email' => 'denny@patroli.com', 'number' => 'GRD-004', 'nik' => '3578010101900004'],
            ['name' => 'Eka Putri', 'email' => 'eka@patroli.com', 'number' => 'GRD-005', 'nik' => '3578010101900005'],
        ];

        foreach ($guardNames as $i => $g) {
            $user = User::create([
                'name' => $g['name'],
                'email' => $g['email'],
                'password' => Hash::make('password'),
                'role' => UserRole::Guard,
                'phone' => '08123456789' . ($i + 2),
                'is_active' => true,
            ]);

            Guard::create([
                'user_id' => $user->id,
                'guard_number' => $g['number'],
                'full_name' => $g['name'],
                'nik' => $g['nik'],
                'phone' => '08123456789' . ($i + 2),
                'address' => 'Jl. Contoh No. ' . ($i + 1) . ', Bali',
                'gender' => $i < 3 ? 'male' : 'female',
                'join_date' => now()->subMonths(rand(1, 12)),
                'emergency_contact' => '081234567800',
                'emergency_name' => 'Keluarga ' . explode(' ', $g['name'])[0],
                'blood_type' => ['A', 'B', 'AB', 'O'][rand(0, 3)],
                'is_active' => true,
            ]);
        }
    }
}
