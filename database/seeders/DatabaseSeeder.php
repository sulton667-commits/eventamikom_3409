<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Partner;
use App\Models\Category;
use App\Models\Event;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        User::firstOrCreate(
            ['email' => 'admin@amikom.ac.id'],
            [
                'name' => 'Admin Amikom',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]
        );

        // 2. Partner User Account
        $partnerUser = User::firstOrCreate(
            ['email' => 'partner@amikom.ac.id'],
            [
                'name' => 'Partner HMSSI Amikom',
                'password' => bcrypt('partner123'),
                'role' => 'partner',
            ]
        );

        // 3. Partner Utama
        $partner = Partner::firstOrCreate(
            ['name' => 'HMSSI Amikom'],
            [
                'user_id' => $partnerUser->id,
                'category' => 'Himpunan Mahasiswa Sistem & Sains Informasi Amikom',
                'status' => 'Aktif',
            ]
        );
        if (!$partner->user_id) {
            $partner->update(['user_id' => $partnerUser->id]);
        }

        // 3. Kategori
        $catMusik = Category::firstOrCreate(['slug' => 'musik'], ['name' => 'Musik']);
        $catEnter = Category::firstOrCreate(['slug' => 'entertaiment'], ['name' => 'Entertaiment']);
        $catIT    = Category::firstOrCreate(['slug' => 'seminar-it'], ['name' => 'Seminar IT']);
        $catSport = Category::firstOrCreate(['slug' => 'futsal'], ['name' => 'Futsal']);

    }}