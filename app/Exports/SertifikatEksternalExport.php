<?php

namespace App\Exports;

use App\Models\SertifikatEksternal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SertifikatEksternalExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;
    protected $userId;

    public function __construct($request, $userId)
    {
        $this->request = $request;
        $this->userId = $userId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = SertifikatEksternal::where('user_id', $this->userId);

        if ($startDate = $this->request->input('start_date')) {
            $endDate = $this->request->input('end_date') ?: $startDate;
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        if ($status = $this->request->input('status')) {
            $query->where('status', $status);
        }

        return $query->orderByDesc('created_at')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pelatihan',
            'Tanggal',
            'Status',
        ];
    }

    public function map($sertifikat): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $sertifikat->judul,
            $sertifikat->created_at->format('d/m/Y'),
            $sertifikat->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
