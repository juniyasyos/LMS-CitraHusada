<?php

namespace App\Exports;

use App\Models\UserProgress;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonitoringExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $search = $this->request->input('search');
        $unitFilter = $this->request->input('unit_name');
        $statusFilter = $this->request->input('status');

        $query = UserProgress::with(['user.unitKerjas', 'materi']);

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nip', 'LIKE', "%{$search}%");
            })->orWhereHas('materi', function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%");
            });
        }

        if ($unitFilter) {
            $query->whereHas('user', function ($q) use ($unitFilter) {
                $q->whereHas('unitKerjas', function($q2) use ($unitFilter) { $q2->where('unit_kerjas.unit_kerja_id', $unitFilter); });
            });
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'NIP',
            'Unit Kerja',
            'Nama Pelatihan',
            'Progres (%)',
            'Status',
            'Nilai',
            'Tanggal Pembaruan',
        ];
    }

    public function map($report): array
    {
        $totalSteps = $report->materi->subMateris->count() + $report->materi->postTests->count();
        $percent = $totalSteps > 0 ? round(($report->urutan_selesai / $totalSteps) * 100) : 0;

        return [
            $report->user->nama,
            "'" . $report->user->nip, // Force string for NIP
            $report->user?->unitKerjas->pluck('unit_name')->join(', ') ?: '-',
            $report->materi->judul,
            $percent . '%',
            $report->status,
            $report->skor_total !== null ? round($report->skor_total, 1) : '-',
            $report->updated_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
