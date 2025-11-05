<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================================
        // 👨‍💼 إنشاء Admins
        // ============================================
        
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'phone' => '01012345678',
        ]);

        // Admin ثاني
        User::create([
            'name' => 'مدير النظام',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'phone' => '01098765432',
        ]);

        // ============================================
        // 👨‍🎓 إنشاء Students للتجربة
        // ============================================
        
        User::create([
            'name' => 'محمد أحمد',
            'email' => 'student@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'student',
            'phone' => '01123456789',
        ]);

        User::create([
            'name' => 'أحمد علي',
            'email' => 'student2@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'student',
            'phone' => '01234567890',
        ]);

        // ============================================
        // 🎲 إنشاء 10 طلاب عشوائيين
        // ============================================
        
        User::factory()
            ->count(10)
            ->student()
            ->create();

        // ============================================
        // 🎲 إنشاء 3 admins عشوائيين
        // ============================================
        
        User::factory()
            ->count(3)
            ->admin()
            ->create();
    }
}
