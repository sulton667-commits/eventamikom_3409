<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Partner;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class PartnerDashboardSeeder extends Seeder
{
    public function run(): void
    {
        $partner = Partner::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'HMSSI Amikom',
                'category' => 'Himpunan Mahasiswa Sistem & Sains Informasi Amikom',
                'status' => 'Aktif'
            ]
        );

        $user = User::where('role', 'partner')->first();
        if ($user) {
            $partner->update(['user_id' => $user->id]);
        }

        $category = Category::first() ?? Category::firstOrCreate(['name' => 'General'], ['slug' => 'general']);

        $e1 = Event::updateOrCreate(['title' => 'LARKOS FC'], [
            'category_id' => $category->id,
            'partner_id' => $partner->id,
            'description' => 'Turnamen Mini Soccer',
            'date' => '2026-07-27 16:26:00',
            'location' => 'Amikom MiniSocer',
            'price' => 0,
            'stock' => 100
        ]);

        $e2 = Event::updateOrCreate(['title' => 'ndx'], [
            'category_id' => $category->id,
            'partner_id' => $partner->id,
            'description' => 'Konser Musik NDX',
            'date' => '2026-07-26 01:20:00',
            'location' => 'kridosono',
            'price' => 100000,
            'stock' => 500
        ]);

        $e3 = Event::updateOrCreate(['title' => 'AI SOCIALISASI'], [
            'category_id' => $category->id,
            'partner_id' => $partner->id,
            'description' => 'Sosialisasi Artificial Intelligence',
            'date' => '2026-07-26 13:00:00',
            'location' => 'Cinema Unit 6',
            'price' => 50000,
            'stock' => 200
        ]);

        $e4 = Event::updateOrCreate(['title' => 'Jazz Night 2025'], [
            'category_id' => $category->id,
            'partner_id' => $partner->id,
            'description' => 'Malam Musik Jazz',
            'date' => '2026-07-28 19:00:00',
            'location' => 'Amikom Baru',
            'price' => 50000,
            'stock' => 300
        ]);

        $txs = [
            ['baba', 'baba@gmail.com', '082324268826', $e1->id, 'TRX-1785266855-gbxtK', 0, '2026-07-28 19:27:00', 'settlement'],
            ['muadz', 'ku@gmail.com', '082324288826', $e1->id, 'TRX-1785259005-mMEnS', 0, '2026-07-28 17:30:00', 'settlement'],
            ['TES', 'awansetiewann999@students.amikom.ac.id', '088215582344', $e2->id, 'TRX-1785238264-C1fES', 105000, '2026-07-28 11:31:00', 'settlement'],
            ['sadsf', 'sdgdfgfd@gmail.com', '76878', $e1->id, 'TRX-1785221415-Am1F3', 0, '2026-07-28 06:50:00', 'settlement'],
            ['sulton', 'sulton667@students.amikom.ac.id', '02923924923', $e3->id, 'TRX-1785173449-BY9E0', 55000, '2026-07-27 17:30:00', 'settlement'],
            ['kmm', 'tedi667@gmail.com', '02923924923', $e2->id, 'TRX-1785160532-x876h', 105000, '2026-07-27 16:25:00', 'pending'],
            ['reza', 'fwzrzal@gmail.com', '088812345677', $e2->id, 'TRX-1785164649-5eOV9', 105000, '2026-07-27 15:04:00', 'pending'],
            ['cayang', 'catang@gmail.com', '1234567890', $e2->id, 'TRX-1785150087-7QsUd', 105000, '2026-07-27 11:01:00', 'settlement'],
            ['NELSON RAYMOND 24.12.3438', 'nelsonraymond@students.amikom.ac.id', '08341223342', $e1->id, 'TRX-1784976175-WZjcX', 0, '2026-07-25 10:42:00', 'settlement'],
            ['zakia', 'zakiatunnisa2004@gmail.com', '085642102544', $e1->id, 'TRX-1784972698-YR6Ly', 0, '2026-07-25 09:44:00', 'settlement'],
            ['zakia', 'zakiatunnisa@gmail.com', '085642102544', $e3->id, 'TRX-1784972506-Owjqo', 55000, '2026-07-25 09:41:00', 'settlement'],
            ['x', 'muadzabdul77@gmail.com', '082324288826', $e4->id, 'TRX-1784920516-fKjVzn', 55000, '2026-07-24 19:15:00', 'settlement'],
            ['aku', 'aku@gmail.com', '082324288826', $e2->id, 'TRX-1784919891-3Vyrz', 0, '2026-07-24 19:04:00', 'settlement'],
            ['babaku', 'muadzabdul77@gmail.com', '082324288826', $e2->id, 'TRX-1784917304-jwF1R', 0, '2026-07-24 18:21:00', 'settlement'],
            ['muass', 'ablmuadz78@gmail.com', '082324208826', $e3->id, 'TRX-1784914616-P1GRq', 55000, '2026-07-24 17:36:00', 'settlement']
        ];

        foreach ($txs as $t) {
            Transaction::updateOrCreate(
                ['order_id' => $t[4]],
                [
                    'customer_name' => $t[0],
                    'customer_email' => $t[1],
                    'customer_phone' => $t[2],
                    'event_id' => $t[3],
                    'total_price' => $t[5],
                    'created_at' => $t[6],
                    'status' => $t[7]
                ]
            );
        }
    }
}
