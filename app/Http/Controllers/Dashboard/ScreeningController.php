<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Screening;
use Illuminate\Support\Facades\Auth;

class ScreeningController extends Controller
{
    protected $screeningService;

    public function __construct(\App\Services\Dashboard\ScreeningService $screeningService)
    {
        $this->screeningService = $screeningService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $filters = $request->only(['search', 'severity']);
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $screenings = $this->screeningService->getPaginatedScreenings($user, $filters, $sort, $direction);

        return view('pages.dashboard.skrining.index', compact('screenings'));
    }

    public function show($id)
    {
        $screening = Screening::findOrFail($id);
        $this->authorize('view', $screening);
        
        $screening->load(['respondent.healthPost', 'respondent.village', 'device', 'screeningPeriod', 'followUp']);

        $decision = json_decode($screening->decision_explanation, true);

        if (request()->wantsJson() || request()->is('*/json')) {
            return $this->respondJson($screening, $decision);
        }

        return view('pages.dashboard.skrining.show', compact('screening', 'decision'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'action_status' => 'required|in:unhandled,in_progress,completed'
        ]);

        $screening = Screening::findOrFail($id);
        $this->authorize('update', $screening);

        $screening->action_status = $request->action_status;
        $screening->save();

        return redirect()->back()->with('success', 'Status tindakan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $screening = Screening::findOrFail($id);
        $this->authorize('delete', $screening);
        
        $screening->delete();

        return redirect()->back()->with('success', 'Data skrining berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $filters = $request->only(['search', 'severity']);

        $query = Screening::filterByRole($user)
            ->with(['respondent.healthPost', 'respondent.village']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('respondent', function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                    ->orWhere('fullname', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['severity']) && $filters['severity'] !== 'all') {
            $query->where('recommendation_level', $filters['severity']);
        }

        $screenings = $query->orderBy('created_at', 'desc')->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Headers
        $headers = [
            'No',
            'Tgl Skrining',
            'Periode Skrining',
            'NIK',
            'Nama Lengkap',
            'Umur',
            'Jenis Kelamin',
            'Sistolik',
            'Diastolik',
            'Gula Darah',
            'Kolesterol',
            'Asam Urat',
            'Status DM',
            'Status HT',
            'Rekomendasi',
            'Status Tindakan',
            'Posyandu'
        ];
        $sheet->fromArray($headers, null, 'A1');

        // Style Headers
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $lastCol = 'Q';
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray($headerStyle);

        $row = 2;
        foreach ($screenings as $index => $s) {
            $r = $s->respondent;
            $age = $r->birthdate ? \Carbon\Carbon::parse($r->birthdate)->age : '-';
            $gender = $r->gender == 'L' ? 'Laki-laki' : 'Perempuan';

            $data = [
                $index + 1,
                $s->created_at->format('Y-m-d H:i'),
                $s->screeningPeriod->name ?? 'Tanpa Periode',
                $r->nik ?? '-',
                $r->fullname ?? '-',
                $age,
                $gender,
                $s->c_sistolik,
                $s->c_diastolik,
                $s->c_gula,
                $s->c_kolesterol,
                $s->c_asam_urat,
                $s->dm_status,
                $s->ht_status,
                $s->recommendation_level,
                $s->action_status == 'unhandled' ? 'Belum' : ($s->action_status == 'in_progress' ? 'Diproses' : 'Selesai'),
                $r->healthPost->name ?? '-'
            ];

            $sheet->fromArray($data, null, 'A' . $row);
            // Force NIK to be string so it doesn't become scientific notation
            $sheet->setCellValueExplicit('D' . $row, $r->nik ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $row++;
        }

        // Style Body
        if ($row > 2) {
            $bodyStyle = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];
            $sheet->getStyle('A2:' . $lastCol . ($row - 1))->applyFromArray($bodyStyle);
        }

        // Auto size columns
        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'excel');
        $writer->save($tempFile);

        return response()->download($tempFile, 'Data_Skrining.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function respondJson($screening, $decision)
    {
        $respondent = $screening->respondent;
        $age = $respondent->birthdate
            ? \Carbon\Carbon::parse($respondent->birthdate)->age
            : '-';

        $tinggi = $screening->c_tinggi ?: 0;
        $berat = $screening->c_berat ?: 0;
        $imt = $tinggi > 0 ? round($berat / (($tinggi / 100) ** 2), 1) : 0;

        return response()->json([
            'id' => $screening->id,
            'respondent' => [
                'fullname' => $respondent->fullname ?? 'Anonim',
                'nik' => $respondent->nik ?? '-',
                'gender' => $respondent->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                'age' => $age,
                'birthdate' => $respondent->birthdate ? \Carbon\Carbon::parse($respondent->birthdate)->format('d M Y') : '-',
            ],
            'screened_at' => $screening->created_at->format('d M Y, H:i') . ' WIB',
            'clinical' => [
                'sistolik' => $screening->c_sistolik,
                'diastolik' => $screening->c_diastolik,
                'gula' => $screening->c_gula,
                'kolesterol' => $screening->c_kolesterol,
                'asam_urat' => $screening->c_asam_urat,
                'berat' => $berat,
                'tinggi' => $tinggi,
                'lingkar_perut' => $screening->c_perut,
                'imt' => $imt,
                'merokok' => $screening->c_merokok ? 'Ya' : 'Tidak',
            ],
            'result' => [
                'dm_status' => $screening->dm_status,
                'dm_severity' => $screening->dm_severity,
                'dm_message' => $decision['dm_msg'] ?? '',
                'dm_action' => $decision['dm_action'] ?? '',
                'ht_status' => $screening->ht_status,
                'ht_severity' => $screening->ht_severity,
                'ht_message' => $decision['ht_msg'] ?? '',
                'ht_action' => $decision['ht_action'] ?? '',
                'recommendation_level' => $screening->recommendation_level,
            ],
            'findings' => $decision['findings'] ?? [],
            'evidence' => $decision['evidence'] ?? [],
        ]);
    }
}
