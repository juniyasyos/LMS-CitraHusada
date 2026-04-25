<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LeaderboardExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $leaderboard;
    protected $rowNumber = 0;

    public function __construct($leaderboard)
    {
        $this->leaderboard = $leaderboard;
    }

    public function collection()
    {
        return $this->leaderboard;
    }

    public function headings(): array
    {
        return [
            'Rank',
            'Nama Lengkap',
            'Unit Kerja',
            'Pelatihan Selesai',
            'Total JPL',
            'Status JPL',
        ];
    }

    public function map($user): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $user->nama,
            $user->unitKerja ? $user->unitKerja->unit_kerja : '-',
            $user->pelatihan_selesai . ' Pelatihan',
            $user->total_jpl,
            ($user->total_jpl ?? 0) >= 20 ? 'Terpenuhi' : 'Belum Terpenuhi',
        ];
    }
}
