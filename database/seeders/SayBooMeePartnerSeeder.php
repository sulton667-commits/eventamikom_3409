<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SayBooMeePartnerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat atau update user SayBooMee
        $user = User::updateOrCreate(
            ['email' => 'sayboomee@gmail.com'],
            [
                'name'     => 'SayBooMee',
                'password' => Hash::make('sayboomee123'),
                'role'     => 'partner',
            ]
        );

        // 2. Link user ke Partner SayBooMee (id=2)
        $partner = Partner::where('name', 'SayBooMee')->first();
        if ($partner) {
            $partner->update(['user_id' => $user->id]);
            echo "Partner SayBooMee (ID: {$partner->id}) dihubungkan ke user ID {$user->id}" . PHP_EOL;
        } else {
            echo "Partner SayBooMee tidak ditemukan!" . PHP_EOL;
        }

        echo "Selesai! Akun: sayboomee@gmail.com / sayboomee123" . PHP_EOL;
    }
}
