<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    public function getDirektur()
    {
        try {
            $direktur = \App\Models\Direktur::first();
            return response()->json([
                'success' => true,
                'data' => $direktur
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data direktur: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDirektur(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'jabatan' => 'required|string|max:255',
                'nik' => 'required|string|max:255',
                'file_ttd' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
            ]);

            $direktur = \App\Models\Direktur::first();
            
            if (!$direktur) {
                $direktur = new \App\Models\Direktur();
            }

            $direktur->nama = $request->nama;
            $direktur->jabatan = $request->jabatan;
            $direktur->nik = $request->nik;

            if ($request->hasFile('file_ttd')) {
                // Hapus file lama jika ada
                if ($direktur->ttd_path && Storage::exists($direktur->ttd_path)) {
                    Storage::delete($direktur->ttd_path);
                }

                $path = $request->file('file_ttd')->store('sertifikat/ttd');
                $direktur->ttd_path = $path;
            }

            $direktur->save();

            return response()->json([
                'success' => true,
                'message' => 'Data direktur berhasil disimpan.',
                'data' => $direktur
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data direktur: ' . $e->getMessage()
            ], 500);
        }
    }

    public function previewSertifikat(\Illuminate\Http\Request $request)
    {
        try {
            $type = $request->query('type', 'depan');
            $direktur = \App\Models\Direktur::first();
            $nama = $direktur->nama ?? 'Nama Direktur';
            $jabatan = $direktur->jabatan ?? 'Jabatan';
            $nik = $direktur->nik ?? '0000.000';

            // Inisialisasi ImageManager dengan driver GD
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            
            $masterFile = $type === 'belakang' 
                ? 'materi/Sertifikat/Master_Sertifikat_Belakang.png'
                : 'materi/Sertifikat/Master_Sertifikat_Depan.png';

            if (!Storage::disk('public')->exists($masterFile)) {
                return response('Master sertifikat tidak ditemukan.', 404);
            }

            $image = $manager->read(Storage::disk('public')->get($masterFile));

            if ($type === 'depan') {
                // ==========================================
                // KOORDINAT TEKS DI SINI (BAGIAN DEPAN)
                // ==========================================
                $x_nama = 2250; $y_nama = 2600;
                $x_jabatan = 2250; $y_jabatan = 2280;
                $x_nik = 2250; $y_nik = 2650;

                // Pastikan font tersedia. Menggunakan font default Windows untuk kemudahan tes di local
                $fontPath = 'C:\Windows\Fonts\arial.ttf';
                
                if (file_exists($fontPath)) {
                    $image->text($nama, $x_nama, $y_nama, function ($font) use ($fontPath) {
                        $font->file($fontPath);
                        $font->size(50);
                        $font->color('#000000');
                        $font->align('center');
                        $font->valign('middle');
                    });
                    
                    $image->text($jabatan, $x_jabatan, $y_jabatan, function ($font) use ($fontPath) {
                        $font->file($fontPath);
                        $font->size(50);
                        $font->color('#000000');
                        $font->align('center');
                        $font->valign('middle');
                    });

                    // $image->text('NIK. ' . $nik, $x_nik, $y_nik, function ($font) use ($fontPath) {
                    //     $font->file($fontPath);
                    //     $font->size(40);
                    //     $font->color('#000000');
                    //     $font->align('center');
                    //     $font->valign('middle');
                    // });
                }

                // ==========================================
                // KOORDINAT TANDA TANGAN DI SINI (DEPAN)
                // ==========================================
                $x_ttd = 1950; 
                $y_ttd = 2250;
                $lebar_ttd = 700; // Lebar gambar tanda tangan (pixels)

                if ($direktur && $direktur->ttd_path && Storage::exists($direktur->ttd_path)) {
                        $ttd = $manager->read(Storage::get($direktur->ttd_path));
                        $ttd->scale(width: $lebar_ttd);
                        $image->place($ttd, 'top-left', $x_ttd, $y_ttd); 
                }
            } else {
                // ==========================================
                // KOORDINAT TEKS DI SINI (BAGIAN BELAKANG)
                // ==========================================
                $x_nama = 3450; $y_nama = 2500;
                $x_jabatan = 3450; $y_jabatan = 2080;
                $x_nik = 3450; $y_nik = 2750;

                // Pastikan font tersedia. Menggunakan font default Windows untuk kemudahan tes di local
                $fontPath = 'C:\Windows\Fonts\arial.ttf';
                
                if (file_exists($fontPath)) {
                    $image->text($nama, $x_nama, $y_nama, function ($font) use ($fontPath) {
                        $font->file($fontPath);
                        $font->size(50);
                        $font->color('#000000');
                        $font->align('center');
                        $font->valign('middle');
                    });
                    
                    $image->text($jabatan, $x_jabatan, $y_jabatan, function ($font) use ($fontPath) {
                        $font->file($fontPath);
                        $font->size(50);
                        $font->color('#000000');
                        $font->align('center');
                        $font->valign('middle');
                    });

                    // $image->text('NIK. ' . $nik, $x_nik, $y_nik, function ($font) use ($fontPath) {
                    //     $font->file($fontPath);
                    //     $font->size(40);
                    //     $font->color('#000000');
                    //     $font->align('center');
                    //     $font->valign('middle');
                    // });
                }

                // ==========================================
                // KOORDINAT TANDA TANGAN DI SINI (BELAKANG)
                // ==========================================
                $x_ttd = 3130; 
                $y_ttd = 2100;
                $lebar_ttd = 700; // Lebar gambar tanda tangan (pixels)

                if ($direktur && $direktur->ttd_path && Storage::exists($direktur->ttd_path)) {
                        $ttd = $manager->read(Storage::get($direktur->ttd_path));
                        $ttd->scale(width: $lebar_ttd);
                        $image->place($ttd, 'top-left', $x_ttd, $y_ttd); 
                }
            }

            return response($image->toPng())->header('Content-Type', 'image/png');

        } catch (\Exception $e) {
            return response('Gagal generate preview: ' . $e->getMessage(), 500);
        }
    }

    public function generateUserSertifikat(\Illuminate\Http\Request $request, $userId, $materiId)
    {
        try {
            $type = $request->query('type', 'depan');

            // Data Direktur
            $direktur = \App\Models\Direktur::first();
            $namaDir = $direktur->nama ?? 'Nama Direktur';
            $jabatanDir = $direktur->jabatan ?? 'Jabatan';
            $nikDir = $direktur->nik ?? '123456789';

            // Data User & Materi
            $user = \App\Models\User::find($userId);
            $materi = \App\Models\Materi::find($materiId);
            $progress = \Illuminate\Support\Facades\DB::table('user_progress')
                ->where('user_id', $userId)
                ->where('materi_id', $materiId)
                ->first();

            $namaUser = $user ? $user->nama : 'Nama Peserta';
            $nomorSurat = $materi ? ($materi->nomor_surat ?? '') : '';
            $judulMateri = $materi ? $materi->judul : 'Judul Pelatihan';
            
            $tanggalMateri = '-';
            if ($materi && $materi->tanggal_upload && $materi->tanggal_selesai) {
                $start = \Carbon\Carbon::parse($materi->tanggal_upload)->format('d M Y');
                $end = \Carbon\Carbon::parse($materi->tanggal_selesai)->format('d M Y');
                $tanggalMateri = $start . ' - ' . $end;
            }

            $durasi = '0 Jam 0 Menit';
            if ($progress && $progress->created_at && $progress->updated_at) {
                $created = \Carbon\Carbon::parse($progress->created_at);
                $updated = \Carbon\Carbon::parse($progress->updated_at);
                $diffInMinutes = $created->diffInMinutes($updated);
                $hours = floor($diffInMinutes / 60);
                $minutes = $diffInMinutes % 60;
                $durasi = "{$hours} Jam {$minutes} Menit";
            }

            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            
            $masterFile = $type === 'belakang' 
                ? 'materi/Sertifikat/Master_Sertifikat_Belakang.png'
                : 'materi/Sertifikat/Master_Sertifikat_Depan.png';

            if (!Storage::disk('public')->exists($masterFile)) {
                return response('Master sertifikat tidak ditemukan.', 404);
            }

            $image = $manager->read(Storage::disk('public')->get($masterFile));

            if ($type === 'depan') {
                // ==========================================
                // UBAH KOORDINAT TEKS DIREKTUR & PESERTA DI SINI
                // ==========================================
                
                // Koordinat Direktur
                $x_namaDir = 2250; $y_namaDir = 2600;
                $x_jabatanDir = 2250; $y_jabatanDir = 2280;
                $x_nikDir = 2250; $y_nikDir = 2650;

                // Koordinat Peserta & Pelatihan
                $x_namaUser = 2250; $y_namaUser = 1160;
                $x_nomorSurat = 2250; $y_nomorSurat = 908;
                $x_judulMateri = 2250; $y_judulMateri = 1440;
                $x_tanggalMateri = 2250; $y_tanggalMateri = 1668;
                $x_durasi = 1545; $y_durasi = 1938;

                //tanggal ditandatangani
                $x_tanggalCetak = 2250; $y_tanggalCetak = 2140;
                $tanggalHariIni = \Carbon\Carbon::now()->translatedFormat('d F Y');

                $fontPath = 'C:\Windows\Fonts\arial.ttf';
                $fontGaret = public_path('storage/materi/Sertifikat/Font/garet/Garet-Heavy.ttf');
                $fontRoboto = public_path('storage/materi/Sertifikat/Font/roboto/Roboto-Regular.ttf');
                
                if (file_exists($fontPath)) {
                    // Render Teks Direktur
                    $image->text($namaDir, $x_namaDir, $y_namaDir, function ($font) use ($fontRoboto) { $font->file($fontRoboto); $font->size(50); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                    $image->text($jabatanDir, $x_jabatanDir, $y_jabatanDir, function ($font) use ($fontRoboto) { $font->file($fontRoboto); $font->size(50); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                    // $image->text('NIK. ' . $nikDir, $x_nikDir, $y_nikDir, function ($font) use ($fontRoboto) { $font->file($fontRoboto); $font->size(40); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                    
                    // Render Teks Peserta
                    // $image->text($namaUser, $x_namaUser, $y_namaUser, function ($font) use ($fontGaret) { $font->file($fontGaret); $font->size(130); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                    $image->text(strtoupper($namaUser), $x_namaUser, $y_namaUser, function ($font) use ($fontGaret) { $font->file($fontGaret); $font->size(130); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                    $image->text('Nomor : ' . $nomorSurat, $x_nomorSurat, $y_nomorSurat, function ($font) use ($fontRoboto) { $font->file($fontRoboto); $font->size(50); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                    $image->text(strtoupper($judulMateri), $x_judulMateri, $y_judulMateri, function ($font) use ($fontGaret) { $font->file($fontGaret); $font->size(110); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                    $image->text('pada tanggal ' . $tanggalMateri, $x_tanggalMateri, $y_tanggalMateri, function ($font) use ($fontRoboto) { $font->file($fontRoboto); $font->size(50); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                    $image->text($durasi, $x_durasi, $y_durasi, function ($font) use ($fontRoboto) { $font->file($fontRoboto); $font->size(50); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                    $image->text('Jember, ' . $tanggalHariIni, $x_tanggalCetak, $y_tanggalCetak, function ($font) use ($fontRoboto) { $font->file($fontRoboto); $font->size(50); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                }

                // ==========================================
                // UBAH KOORDINAT TANDA TANGAN (DEPAN)
                // ==========================================
                $x_ttd = 1950; 
                $y_ttd = 2250;
                $lebar_ttd = 700;

                if ($direktur && $direktur->ttd_path && Storage::exists($direktur->ttd_path)) {
                        $ttd = $manager->read(Storage::get($direktur->ttd_path));
                        $ttd->scale(width: $lebar_ttd);
                        $image->place($ttd, 'top-left', $x_ttd, $y_ttd); 
                }
            } else {
                // ==========================================
                // UBAH KOORDINAT TEKS DIREKTUR & PESERTA DI SINI
                // ==========================================
                
                // Koordinat Direktur
                $x_namaDir = 3450; $y_namaDir = 2500;
                $x_jabatanDir = 3450; $y_jabatanDir = 2080;
                $x_nikDir = 3450; $y_nikDir = 2750;

                $fontPath = 'C:\Windows\Fonts\arial.ttf';

                if (file_exists($fontPath)) {
                    // Render Teks Direktur
                    $image->text($namaDir, $x_namaDir, $y_namaDir, function ($font) use ($fontPath) { $font->file($fontPath); $font->size(50); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                    $image->text($jabatanDir, $x_jabatanDir, $y_jabatanDir, function ($font) use ($fontPath) { $font->file($fontPath); $font->size(50); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                    // $image->text('NIK. ' . $nikDir, $x_nikDir, $y_nikDir, function ($font) use ($fontPath) { $font->file($fontPath); $font->size(40); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                }

                // ==========================================
                // TABEL RINCIAN MATERI (BAGIAN BELAKANG)
                // Kolom: Materi | Pemateri | JPL
                // Baris terakhir: Total (gabung kolom Materi+Pemateri) | total JPL
                // ==========================================
                try {
                    $tableStartX = 750; // kiri
                    $tableStartY = 705; // atas
                    $col1Width = 1500; // Materi
                    $col2Width = 900;  // Pemateri
                    $col3Width = 600;  // JPL
                    $rowHeight = 230;  

                    $col1End = $tableStartX + $col1Width;
                    $col2End = $col1End + $col2Width;
                    $tableEndX = $col2End + $col3Width;

                    // header + data + total
                    $tableHeight = $rowHeight * 3;
                    $tableWidth = $tableEndX - $tableStartX;

                    // KETEBALAN GARIS BARU (Disamakan & Diperbesar 2x Lipat)
                    $lineThickness = 12;

                    // 1. Outer rectangle (Garis Luar Tabel - Tetap Pakai Kotak)
                    $image->drawRectangle($tableStartX, $tableStartY, function ($rectangle) use ($tableWidth, $tableHeight, $lineThickness) {
                        $rectangle->size($tableWidth, $tableHeight);
                        $rectangle->border('#000000', $lineThickness);
                    });

                    // 2. Horizontal separators (Garis Pembatas Mendatar - SEKARANG PAKAI RECTANGLE TIPIS)
                    // Garis Mendatar Pertama
                    $image->drawRectangle($tableStartX, $tableStartY + $rowHeight - ($lineThickness/2), function ($rectangle) use ($tableWidth, $lineThickness) {
                        $rectangle->size($tableWidth, $lineThickness); // Kotak dengan tinggi 12px memanjang
                        $rectangle->background('#000000'); // Diisi warna hitam penuh agar jadi garis tebal
                    });
                    // Garis Mendatar Kedua
                    $image->drawRectangle($tableStartX, $tableStartY + ($rowHeight * 2) - ($lineThickness/2), function ($rectangle) use ($tableWidth, $lineThickness) {
                        $rectangle->size($tableWidth, $lineThickness);
                        $rectangle->background('#000000');
                    });

                    // 3. Vertical separators (Garis Pembatas Tegak - SEKARANG PAKAI RECTANGLE TIPIS)
                    // Garis Vertikal Kolom 1 (Berhenti sebelum baris TOTAL, tingginya hanya 2 baris)
                    $image->drawRectangle($col1End - ($lineThickness/2), $tableStartY, function ($rectangle) use ($rowHeight, $lineThickness) {
                        $rectangle->size($lineThickness, $rowHeight * 2); // Kotak dengan lebar 12px meninggi
                        $rectangle->background('#000000');
                    });
                    // Garis Vertikal Kolom JPL (Tembus sampai bawah, tingginya full 3 baris)
                    $image->drawRectangle($col2End - ($lineThickness/2), $tableStartY, function ($rectangle) use ($tableHeight, $lineThickness) {
                        $rectangle->size($lineThickness, $tableHeight);
                        $rectangle->background('#000000');
                    });

                    // Header & Cell texts
                    if (file_exists($fontPath)) {
                        $headerFontSize = 52; 
                        $cellFontSize = 48;   

                        // 1. Render Teks Header (Rata Tengah)
                        $image->text('Materi', $tableStartX + ($col1Width/2), $tableStartY + ($rowHeight/2), function ($font) use ($fontPath, $headerFontSize) { $font->file($fontPath); $font->size($headerFontSize); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                        $image->text('Pemateri', $col1End + ($col2Width/2), $tableStartY + ($rowHeight/2), function ($font) use ($fontPath, $headerFontSize) { $font->file($fontPath); $font->size($headerFontSize); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                        $image->text('JPL', $col2End + ($col3Width/2), $tableStartY + ($rowHeight/2), function ($font) use ($fontPath, $headerFontSize) { $font->file($fontPath); $font->size($headerFontSize); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });

                        // Data row
                        $materiTitle = $materi ? $materi->judul : '-';
                        $materiPemateri = $materi ? ($materi->nama_pemateri ?? '-') : '-';
                        $materiJpl = $materi ? (int) $materi->jam_pelajaran : 0;

                        // 2. Render Teks Data (Rata Tengah)
                        $image->text($materiTitle, $tableStartX + ($col1Width/2), $tableStartY + $rowHeight + ($rowHeight/2), function ($font) use ($fontPath, $cellFontSize) { $font->file($fontPath); $font->size($cellFontSize); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                        $image->text($materiPemateri, $col1End + ($col2Width/2), $tableStartY + $rowHeight + ($rowHeight/2), function ($font) use ($fontPath, $cellFontSize) { $font->file($fontPath); $font->size($cellFontSize); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                        $image->text((string)$materiJpl, $col2End + ($col3Width/2), $tableStartY + $rowHeight + ($rowHeight/2), function ($font) use ($fontPath, $cellFontSize) { $font->file($fontPath); $font->size($cellFontSize); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });

                        // 3. Render Baris Total
                        $totalJpl = $materiJpl; 
                        $centerTotalX = $tableStartX + (($col2End - $tableStartX) / 2);
                        
                        $image->text('TOTAL', $centerTotalX, $tableStartY + ($rowHeight*2) + ($rowHeight/2), function ($font) use ($fontPath, $cellFontSize) { $font->file($fontPath); $font->size($cellFontSize); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                        $image->text((string)$totalJpl, $col2End + ($col3Width/2), $tableStartY + ($rowHeight*2) + ($rowHeight/2), function ($font) use ($fontPath, $cellFontSize) { $font->file($fontPath); $font->size($cellFontSize); $font->color('#000000'); $font->align('center'); $font->valign('middle'); });
                    }
                } catch (\Exception $e) {
                    // tetap lanjutkan proses jika gagal menggambar tabel
                }
                // ==========================================
                // UBAH KOORDINAT TANDA TANGAN (BELAKANG)
                // ==========================================
                $x_ttd = 3130; 
                $y_ttd = 2100;
                $lebar_ttd = 700;

                if ($direktur && $direktur->ttd_path && Storage::exists($direktur->ttd_path)) {
                        $ttd = $manager->read(Storage::get($direktur->ttd_path));
                        $ttd->scale(width: $lebar_ttd);
                        $image->place($ttd, 'top-left', $x_ttd, $y_ttd); 
                }
            }

            return response($image->toPng())->header('Content-Type', 'image/png');

        } catch (\Exception $e) {
            return response('Gagal generate sertifikat user: ' . $e->getMessage(), 500);
        }
    }

    public function showValidasi($userId, $materiId)
    {
        $sertifikat = \App\Models\Sertifikat::with([
            'user.unitKerja', 
            'user.jenisTenaga', 
            'materi.subMateris', 
            'materi.postTests'
        ])
        ->where('user_id', $userId)
        ->where('materi_id', $materiId)
        ->firstOrFail();

        $progress = \App\Models\UserProgress::where('user_id', $sertifikat->user_id)
            ->where('materi_id', $sertifikat->materi_id)
            ->first();

        // Get post tests and user scores
        $postTests = [];
        if ($sertifikat->materi && $sertifikat->materi->postTests) {
            foreach ($sertifikat->materi->postTests as $pt) {
                $score = \App\Models\SkorUser::where('progress_id', $progress->progress_id ?? 0)
                    ->where('post_test_id', $pt->post_test_id)
                    ->first();
                $postTests[] = [
                    'judul' => 'Kuis: ' . $pt->judul,
                    'minimal' => 75,
                    'skor' => $score ? $score->skor : 0
                ];
            }
        }

        return view('Admin_Views.validasi-pelatihan', compact('sertifikat', 'progress', 'postTests'));
    }

    public function processValidasi(Request $request, $userId, $materiId)
    {
        try {
            $sertifikat = \App\Models\Sertifikat::with(['user', 'materi'])
                ->where('user_id', $userId)
                ->where('materi_id', $materiId)
                ->firstOrFail();
            $action = $request->input('action');
            
            if ($action === 'tolak') {
                $request->validate([
                    'deskripsi' => 'required|string'
                ], [
                    'deskripsi.required' => 'Catatan evaluasi wajib diisi jika menolak validasi.'
                ]);

                $sertifikat->status = 'Tidak Disetujui';
                $sertifikat->deskripsi = $request->input('deskripsi');
                $sertifikat->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Validasi berhasil ditolak.'
                ]);
            } elseif ($action === 'setuju') {
                $request->validate([
                    'nomor_surat' => 'required|string|max:255',
                    'deskripsi' => 'nullable|string'
                ], [
                    'nomor_surat.required' => 'Nomor sertifikat wajib diisi sebelum menyetujui.'
                ]);

                $user = $sertifikat->user;
                $materi = $sertifikat->materi;

                if ($materi) {
                    $materi->nomor_surat = $request->input('nomor_surat');
                    $materi->save();
                }
                
                $namaMateri = $materi ? preg_replace('/[^A-Za-z0-9\-]/', '_', $materi->judul) : 'Materi';
                $namaUser = $user ? preg_replace('/[^A-Za-z0-9\-]/', '_', $user->nama) : 'User';
                $tanggal = now()->format('Ymd');
                
                $fileName = "{$namaMateri}_{$namaUser}_{$tanggal}.pdf";
                
                $requestDepan = Request::create('/', 'GET', ['type' => 'depan']);
                $imgDepanResponse = $this->generateUserSertifikat($requestDepan, $user->user_id, $materi->materi_id);
                
                if ($imgDepanResponse->getStatusCode() !== 200) {
                    $errorMsg = $imgDepanResponse->getContent();
                    $decoded = json_decode($errorMsg, true);
                    if (json_last_error() === JSON_ERROR_NONE && isset($decoded['message'])) {
                        $errorMsg = $decoded['message'];
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat gambar sertifikat depan: ' . $errorMsg
                    ], 500);
                }
                $imgDepanBase64 = base64_encode($imgDepanResponse->getContent());
                
                $requestBelakang = Request::create('/', 'GET', ['type' => 'belakang']);
                $imgBelakangResponse = $this->generateUserSertifikat($requestBelakang, $user->user_id, $materi->materi_id);
                
                if ($imgBelakangResponse->getStatusCode() !== 200) {
                    $errorMsg = $imgBelakangResponse->getContent();
                    $decoded = json_decode($errorMsg, true);
                    if (json_last_error() === JSON_ERROR_NONE && isset($decoded['message'])) {
                        $errorMsg = $decoded['message'];
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat gambar sertifikat belakang: ' . $errorMsg
                    ], 500);
                }
                $imgBelakangBase64 = base64_encode($imgBelakangResponse->getContent());
                
                $html = '
                    <html>
                    <head>
                        <style>
                            @page { margin: 0px; size: A4 landscape; }
                            body { margin: 0px; padding: 0px; }
                            img { width: 100%; height: 100%; object-fit: contain; }
                        </style>
                    </head>
                    <body>
                        <img src="data:image/png;base64,' . $imgDepanBase64 . '" />
                        <div style="page-break-before: always;"></div>
                        <img src="data:image/png;base64,' . $imgBelakangBase64 . '" />
                    </body>
                    </html>
                ';
                
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'landscape');
                
                $path = 'materi/Sertifikat/Generate/' . $fileName;
                Storage::put($path, $pdf->output());
                
                if ($sertifikat->status !== 'Disetujui') {
                    if ($user && $materi) {
                        $user->total_jpl += $materi->jam_pelajaran;
                        $user->save();
                    }
                }

                $sertifikat->status = 'Disetujui';
                $sertifikat->deskripsi = $request->input('deskripsi');
                $sertifikat->image_path = $path;
                $sertifikat->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Sertifikat berhasil disetujui dan dibuat.'
                ]);
            }
            
            return response()->json(['success' => false, 'message' => 'Aksi tidak valid.'], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses validasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showUserSertifikat()
    {
        $user = auth()->user();
        
        $sertifikatsInternal = \App\Models\Sertifikat::with('materi')
            ->where('user_id', $user->user_id)
            ->get();
            
        $sertifikatsEksternal = \App\Models\SertifikatEksternal::where('user_id', $user->user_id)
            ->whereIn('status', ['Disetujui', 'Tidak Disetujui'])
            ->get();
            
        return view('page-sertif', compact('sertifikatsInternal', 'sertifikatsEksternal'));
    }

    public function uploadSertifikatEksternal(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file_sertifikat' => 'required|file|mimes:pdf|max:5120',
        ], [
            'judul.required' => 'Judul sertifikat wajib diisi.',
            'file_sertifikat.required' => 'File sertifikat wajib diunggah.',
            'file_sertifikat.mimes' => 'Format file sertifikat harus berupa PDF.',
            'file_sertifikat.max' => 'Ukuran file sertifikat maksimal adalah 5MB.',
        ]);

        try {
            $user = auth()->user();
            $file = $request->file('file_sertifikat');
            
            // Penamaan file unik
            $safeJudul = preg_replace('/[^A-Za-z0-9\-]/', '_', $request->input('judul'));
            $fileName = 'Sertifikat_Eksternal_' . $user->user_id . '_' . time() . '_' . $safeJudul . '.pdf';
            
            // Simpan file ke storage public
            $path = $file->storeAs('materi/Sertifikat/SertifikatEksternal', $fileName);

            \App\Models\SertifikatEksternal::create([
                'user_id' => $user->user_id,
                'judul' => $request->input('judul'),
                'image_path' => $path,
                'status' => 'Belum Disetujui',
            ]);

            return redirect()->back()->with('success', 'Sertifikat eksternal berhasil diunggah dan menunggu verifikasi admin.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunggah sertifikat: ' . $e->getMessage())->withInput();
        }
    }
}
