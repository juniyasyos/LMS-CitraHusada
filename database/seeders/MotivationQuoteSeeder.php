<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MotivationQuote;

class MotivationQuoteSeeder extends Seeder
{
    public function run(): void
    {
        $quotes = [
            // Awal Kuis
            ['judul' => 'Santai aja, baca pelan-pelan.', 'deskripsi' => 'Kamu pasti bisa jawab dengan baik.', 'kondisi' => 'awal kuis'],
            ['judul' => 'Tidak perlu buru-buru.', 'deskripsi' => 'Fokus pada satu soal dulu ya.', 'kondisi' => 'awal kuis'],
            ['judul' => 'Kamu sudah belajar.', 'deskripsi' => 'Sekarang tinggal tunjukin kemampuanmu.', 'kondisi' => 'awal kuis'],
            ['judul' => 'Percaya sama jawabanmu.', 'deskripsi' => 'Kamu lebih paham dari yang kamu kira.', 'kondisi' => 'awal kuis'],
            ['judul' => 'Tarik napas panjang.', 'deskripsi' => 'Mulai dengan bismillah dan ketenangan.', 'kondisi' => 'awal kuis'],

            // Kelipatan 3
            ['judul' => 'Bagus sekali!', 'deskripsi' => 'Terus pertahankan fokusmu.', 'kondisi' => 'kelipatan 3'],
            ['judul' => 'Luar biasa!', 'deskripsi' => 'Kamu menyelesaikan soal dengan presisi.', 'kondisi' => 'kelipatan 3'],
            ['judul' => 'Tetap semangat.', 'deskripsi' => 'Langkahmu sudah berada di jalur yang benar.', 'kondisi' => 'kelipatan 3'],
            ['judul' => 'Sedikit lagi.', 'deskripsi' => 'Kamu pasti bisa menyelesaikan semuanya.', 'kondisi' => 'kelipatan 3'],
            ['judul' => 'Pertahankan Ritmenya.', 'deskripsi' => 'Jangan lengah, terus buktikan kemampuanmu.', 'kondisi' => 'kelipatan 3'],

            // Akhir Kuis
            ['judul' => 'Kalau ragu, periksa lagi.', 'deskripsi' => 'Ini kesempatan terakhir sebelum submit.', 'kondisi' => 'akhir kuis'],
            ['judul' => 'Sedikit lagi selesai.', 'deskripsi' => 'Mari pastikan jawabanmu sudah yang terbaik!', 'kondisi' => 'akhir kuis'],
            ['judul' => 'Satu soal demi satu soal.', 'deskripsi' => 'Sekarang kamu sudah sampai di tujuan.', 'kondisi' => 'akhir kuis'],
            ['judul' => 'Kamu sudah sampai di sini.', 'deskripsi' => 'Itu artinya kamu siap untuk mengumpulkan.', 'kondisi' => 'akhir kuis'],
            ['judul' => 'Jangan lupa cek kembali.', 'deskripsi' => 'Sebuah prosedur kecil bisa menyelamatkan nyawa pasien.', 'kondisi' => 'akhir kuis'],
        ];

        foreach ($quotes as $quote) {
            MotivationQuote::create($quote);
        }
    }
}
