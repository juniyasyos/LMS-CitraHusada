<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PelatihanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $users;
    protected $rowNumber = 0;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Karyawan',
            'Unit Kerja',
            'Pelatihan yang Telah Diikuti',
            'JPL',
            'Status',
        ];
    }

    public function map($user): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $user->nama,
            $user->unitKerja ? $user->unitKerja->unit_kerja : '-',
            ($user->pelatihan_selesai ?? 0) . ' Pelatihan',
            $user->total_jpl,
            ($user->total_jpl ?? 0) >= 20 ? 'Terpenuhi' : 'Belum Terpenuhi',
        ];
    }
}
