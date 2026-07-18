<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CapaianController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $periods = \App\Models\ScreeningPeriod::orderBy('start_date', 'desc')->get();
        $activePeriod = $periods->where('is_active', true)->first();
        
        $selectedPeriodId = $request->input('period_id', $activePeriod ? $activePeriod->id : null);

        $query = Village::query();

        if ($user && $user->isAdminPosyandu()) {
            $query->whereHas('healthPosts', function($q) use ($user) {
                $q->where('id', $user->health_post_id);
            });
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $capaianList = $query->orderBy('name', 'asc')->get()->map(function($village) use ($selectedPeriodId) {
            $respondentQuery = $village->respondents();
            
            $screenedCountQuery = clone $respondentQuery;
            $screenedCount = $screenedCountQuery->whereHas('screenings', function($sq) use ($selectedPeriodId) {
                if ($selectedPeriodId) $sq->where('screening_period_id', $selectedPeriodId);
            })->count();

            $htCountQuery = clone $respondentQuery;
            $htCount = $htCountQuery->whereHas('screenings', function($sq) use ($selectedPeriodId) {
                if ($selectedPeriodId) $sq->where('screening_period_id', $selectedPeriodId);
                $sq->whereIn('ht_status', ['Risiko Tinggi', 'Terdiagnosa']);
            })->count();

            $dmCountQuery = clone $respondentQuery;
            $dmCount = $dmCountQuery->whereHas('screenings', function($sq) use ($selectedPeriodId) {
                if ($selectedPeriodId) $sq->where('screening_period_id', $selectedPeriodId);
                $sq->whereIn('dm_status', ['Risiko Tinggi', 'Terdiagnosa']);
            })->count();

            $village->screened_count = $screenedCount;
            $village->ht_count = $htCount;
            $village->dm_count = $dmCount;

            $village->screened_pct = $village->target_usia_produktif > 0 ? round(($screenedCount / $village->target_usia_produktif) * 100) : 0;
            $village->ht_pct = $village->target_ht > 0 ? round(($htCount / $village->target_ht) * 100) : 0;
            $village->dm_pct = $village->target_dm > 0 ? round(($dmCount / $village->target_dm) * 100) : 0;

            return $village;
        });

        return view('pages.dashboard.capaian.index', compact('capaianList', 'periods', 'selectedPeriodId'));
    }

    public function updateTarget(Request $request, $id)
    {
        $village = Village::findOrFail($id);
        
        $request->validate([
            'target_usia_produktif' => 'required|numeric|min:0',
            'target_ht' => 'required|numeric|min:0',
            'target_dm' => 'required|numeric|min:0',
        ]);

        $village->update([
            'target_usia_produktif' => $request->target_usia_produktif,
            'target_ht' => $request->target_ht,
            'target_dm' => $request->target_dm,
        ]);

        return back()->with('success', 'Target desa berhasil diperbarui!');
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $periods = \App\Models\ScreeningPeriod::orderBy('start_date', 'desc')->get();
        $activePeriod = $periods->where('is_active', true)->first();
        $selectedPeriodId = $request->input('period_id', $activePeriod ? $activePeriod->id : null);

        $periodName = 'Semua Periode';
        if ($selectedPeriodId) {
            $period = $periods->where('id', $selectedPeriodId)->first();
            if ($period) $periodName = $period->name;
        }

        $query = Village::query();
        if ($user && $user->isAdminPosyandu()) {
            $query->whereHas('healthPosts', function($q) use ($user) {
                $q->where('id', $user->health_post_id);
            });
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $villages = $query->orderBy('name', 'asc')->get()->map(function($village) use ($selectedPeriodId) {
            $respondentQuery = $village->respondents();
            $village->screened_count = (clone $respondentQuery)->whereHas('screenings', function($sq) use ($selectedPeriodId) {
                if ($selectedPeriodId) $sq->where('screening_period_id', $selectedPeriodId);
            })->count();
            $village->ht_count = (clone $respondentQuery)->whereHas('screenings', function($sq) use ($selectedPeriodId) {
                if ($selectedPeriodId) $sq->where('screening_period_id', $selectedPeriodId);
                $sq->whereIn('ht_status', ['Risiko Tinggi', 'Terdiagnosa']);
            })->count();
            $village->dm_count = (clone $respondentQuery)->whereHas('screenings', function($sq) use ($selectedPeriodId) {
                if ($selectedPeriodId) $sq->where('screening_period_id', $selectedPeriodId);
                $sq->whereIn('dm_status', ['Risiko Tinggi', 'Terdiagnosa']);
            })->count();
            return $village;
        });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Styles
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ];

        $dataStyle = [
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ];

        // Headers
        $sheet->mergeCells('A1:A3');
        $sheet->setCellValue('A1', 'NO');
        $sheet->mergeCells('B1:B3');
        $sheet->setCellValue('B1', 'NAMA DESA');
        
        $sheet->mergeCells('C1:C2');
        $sheet->setCellValue('C1', 'SASARAN');
        $sheet->setCellValue('C3', 'USIA PRODUKTIF');

        $sheet->mergeCells('D1:I1');
        $sheet->setCellValue('D1', 'HASIL CAPAIAN PERDESA');
        
        $sheet->mergeCells('D2:E2');
        $sheet->setCellValue('D2', 'USIA PRODUKTIF');
        $sheet->setCellValue('D3', 'CAPAIAN');
        $sheet->setCellValue('E3', '%');

        $sheet->mergeCells('F2:G2');
        $sheet->setCellValue('F2', 'HIPERTENSI');
        $sheet->setCellValue('F3', 'CAPAIAN');
        $sheet->setCellValue('G3', '%');

        $sheet->mergeCells('H2:I2');
        $sheet->setCellValue('H2', 'DIABETES MELITUS');
        $sheet->setCellValue('H3', 'CAPAIAN');
        $sheet->setCellValue('I3', '%');

        $sheet->mergeCells('J1:J3');
        $sheet->setCellValue('J1', 'KET');

        $sheet->getStyle('A1:J3')->applyFromArray($headerStyle);
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Data
        $row = 4;
        $no = 1;
        $sumSasaranUsia = 0;
        $sumSasaranHt = 0;
        $sumSasaranDm = 0;
        $sumCapaianUsia = 0;
        $sumCapaianHt = 0;
        $sumCapaianDm = 0;

        foreach ($villages as $v) {
            $pctUsia = $v->target_usia_produktif > 0 ? round(($v->screened_count / $v->target_usia_produktif) * 100) : 0;
            $pctHt = $v->target_ht > 0 ? round(($v->ht_count / $v->target_ht) * 100) : 0;
            $pctDm = $v->target_dm > 0 ? round(($v->dm_count / $v->target_dm) * 100) : 0;

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $v->name);
            $sheet->setCellValue('C' . $row, $v->target_usia_produktif);
            $sheet->setCellValue('D' . $row, $v->screened_count);
            $sheet->setCellValue('E' . $row, $pctUsia . '%');
            $sheet->setCellValue('F' . $row, $v->ht_count);
            $sheet->setCellValue('G' . $row, $pctHt . '%');
            $sheet->setCellValue('H' . $row, $v->dm_count);
            $sheet->setCellValue('I' . $row, $pctDm . '%');
            $sheet->setCellValue('J' . $row, '');
            $sheet->getStyle("A{$row}:J{$row}")->applyFromArray($dataStyle);

            $sumSasaranUsia += $v->target_usia_produktif;
            $sumSasaranHt += $v->target_ht;
            $sumSasaranDm += $v->target_dm;
            $sumCapaianUsia += $v->screened_count;
            $sumCapaianHt += $v->ht_count;
            $sumCapaianDm += $v->dm_count;
            $row++;
        }

        // JUMLAH row
        $sheet->mergeCells("A{$row}:B{$row}");
        $sheet->setCellValue("A{$row}", 'JUMLAH');
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue("C{$row}", $sumSasaranUsia);
        $sheet->setCellValue("D{$row}", $sumCapaianUsia);
        $sumPctUsia = $sumSasaranUsia > 0 ? round(($sumCapaianUsia / $sumSasaranUsia) * 100) : 0;
        $sheet->setCellValue("E{$row}", $sumPctUsia . '%');

        $sheet->setCellValue("F{$row}", $sumCapaianHt);
        $sumPctHt = $sumSasaranHt > 0 ? round(($sumCapaianHt / $sumSasaranHt) * 100) : 0;
        $sheet->setCellValue("G{$row}", $sumPctHt . '%');

        $sheet->setCellValue("H{$row}", $sumCapaianDm);
        $sumPctDm = $sumSasaranDm > 0 ? round(($sumCapaianDm / $sumSasaranDm) * 100) : 0;
        $sheet->setCellValue("I{$row}", $sumPctDm . '%');
        
        $sheet->getStyle("A{$row}:J{$row}")->applyFromArray($headerStyle);
        $row++;

        // SASARAN row
        $sheet->mergeCells("A{$row}:C{$row}");
        $sheet->setCellValue("A{$row}", 'SASARAN');
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("D{$row}:E{$row}");
        $sheet->setCellValue("D{$row}", $sumSasaranUsia);
        $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("F{$row}:G{$row}");
        $sheet->setCellValue("F{$row}", $sumSasaranHt);
        $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("H{$row}:I{$row}");
        $sheet->setCellValue("H{$row}", $sumSasaranDm);
        $sheet->getStyle("H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("A{$row}:J{$row}")->applyFromArray($dataStyle);
        $sheet->getStyle("A{$row}:J{$row}")->getFont()->setBold(true);

        $writer = new Xlsx($spreadsheet);
        $filename = "Data_Capaian_PTM_" . str_replace(' ', '_', $periodName) . ".xlsx";
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}
