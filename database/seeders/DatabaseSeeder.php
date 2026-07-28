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

        // 2. Partner Utama
        $partner = Partner::firstOrCreate(
            ['name' => 'HMSSI Amikom'],
            [
                'category' => 'Himpunan Mahasiswa Sistem & Sains Informasi Amikom',
                'status' => 'Aktif',
            ]
        );

        // 3. Kategori
        $catMusik = Category::firstOrCreate(['slug' => 'musik'], ['name' => 'Musik']);
        $catEnter = Category::firstOrCreate(['slug' => 'entertaiment'], ['name' => 'Entertaiment']);
        $catIT    = Category::firstOrCreate(['slug' => 'seminar-it'], ['name' => 'Seminar IT']);
        $catSport = Category::firstOrCreate(['slug' => 'futsal'], ['name' => 'Futsal']);

        // 4. Events
        $e1 = Event::firstOrCreate(
            ['title' => 'ndx'],
            [
                'category_id' => $catMusik->id,
                'partner_id'  => $partner->id,
                'description' => 'ayolahh bisa',
                'date'        => '2026-07-26 01:20:00',
                'location'    => 'kridosono',
                'price'       => 100000,
                'stock'       => 7,
            ]
        );

        $e2 = Event::firstOrCreate(
            ['title' => 'Jazz Night 2025'],
            [
                'category_id' => $catEnter->id,
                'partner_id'  => $partner->id,
                'description' => 'Nikmati malam yang indah dengan alunan musik jazz yang merdu.',
                'date'        => '2026-07-28 19:00:00',
                'location'    => 'Amikom Baru',
                'price'       => 50000,
                'stock'       => 100,
            ]
        );

        $e3 = Event::firstOrCreate(
            ['title' => 'AI SOSIALISASI'],
            [
                'category_id' => $catIT->id,
                'partner_id'  => $partner->id,
                'description' => 'Sosialisasi perkembangan teknologi AI terkini.',
                'date'        => '2026-07-26 10:00:00',
                'location'    => 'Amikom Cinema',
                'price'       => 50000,
                'stock'       => 50,
            ]
        );

        $e4 = Event::firstOrCreate(
            ['title' => 'LARKOS FC'],
            [
                'category_id' => $catSport->id,
                'partner_id'  => $partner->id,
                'description' => 'Turnamen Futsal LARKOS FC Amikom.',
                'date'        => '2026-07-27 15:00:00',
                'location'    => 'Lapangan Futsal Amikom',
                'price'       => 35000,
                'stock'       => 30,
            ]
        );

        // Update semua event yang belum punya partner_id
        Event::whereNull('partner_id')->update(['partner_id' => $partner->id]);

        // 5. Ulasan / Reviews Sesuai Screenshot Gambar 2
        Review::firstOrCreate(
            ['user_name' => 'MUHAMAD SULTHON 24.12.3409', 'comment' => 'yress'],
            [
                'event_id'   => $e3->id,
                'partner_id' => $partner->id,
                'rating'     => 5,
                'created_at' => now()->subHours(12),
            ]
        );

        Review::firstOrCreate(
            ['user_name' => 'Zakiatun Nisa', 'comment' => 'yeaah gacorrrrrrrr'],
            [
                'event_id'   => $e4->id,
                'partner_id' => $partner->id,
                'rating'     => 5,
                'created_at' => now()->subDays(2),
            ]
        );

        Review::firstOrCreate(
            ['user_name' => 'Abdul Muadz', 'comment' => 'kurang'],
            [
                'event_id'   => $e2->id,
                'partner_id' => $partner->id,
                'rating'     => 1,
                'created_at' => now()->subDays(3),
            ]
        );

        Review::firstOrCreate(
            ['user_name' => 'Abdul Muadz', 'comment' => 'gacor wakk'],
            [
                'event_id'   => $e1->id,
                'partner_id' => $partner->id,
                'rating'     => 5,
                'created_at' => now()->subDays(3),
            ]
        );
    }
}