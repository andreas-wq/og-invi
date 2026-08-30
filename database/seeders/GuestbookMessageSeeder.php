<?php

namespace Database\Seeders;

use App\Models\GuestbookMessage;
use Illuminate\Database\Seeder;

class GuestbookMessageSeeder extends Seeder
{
    /**
     * Seed contoh doa & ucapan tamu.
     */
    public function run(): void
    {
        $samples = [
            [
                'name' => 'Budi & Sari',
                'message' => 'Selamat menempuh hidup baru, Osa & Gelar! Semoga menjadi keluarga yang bahagia dan penuh berkah. 🙏',
                'attendance' => 'Hadir',
            ],
            [
                'name' => 'Keluarga Wijaya',
                'message' => 'Turut berbahagia untuk kedua mempelai. Sampai jumpa di hari bahagia kalian!',
                'attendance' => 'Hadir',
            ],
            [
                'name' => 'Andi Pratama',
                'message' => 'Mohon maaf belum bisa hadir. Selamat atas pernikahannya, doa terbaik selalu dari jauh!',
                'attendance' => 'Tidak hadir',
            ],
            [
                'name' => 'Rina Kartika',
                'message' => 'Wah, akhirnya ya! Selamat ya Osa & Gelar, semoga langgeng sampai kakek-nenek 💐',
                'attendance' => null,
            ],
        ];

        foreach ($samples as $sample) {
            GuestbookMessage::create($sample);
        }
    }
}
