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
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

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
            ->with(['respondent.healthPost', 'respondent.village', 'screeningPeriod']);

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

        $query->orderBy('created_at', 'desc');

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // Tambahkan BOM (Byte Order Mark) agar karakter UTF-8 terbaca sempurna di Microsoft Excel
            fputs($handle, "\xEF\xBB\xBF");

            // Tulis Header
            fputcsv($handle, [
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
            ]);

            $index = 1;
            // Gunakan cursor() untuk mengambil data baris per baris tanpa membebani memori (Lazy Collection)
            foreach ($query->cursor() as $s) {
                $r = $s->respondent;
                $age = $r->birthdate ? \Carbon\Carbon::parse($r->birthdate)->age : '-';
                $gender = $r->gender == 'L' ? 'Laki-laki' : 'Perempuan';

                // Tambahkan tanda petik pada NIK agar Excel membacanya sebagai string (mencegah format eksponensial)
                $nik = $r->nik ? "'" . $r->nik : '-';

                // Tulis data baris per baris langsung ke output buffer browser (Stream)
                fputcsv($handle, [
                    $index,
                    $s->created_at->format('Y-m-d H:i'),
                    $s->screeningPeriod->name ?? 'Tanpa Periode',
                    $nik,
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
                ]);

                $index++;
            }

            fclose($handle);
        }, 'Data_Skrining.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
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
