<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Respondent;
use Illuminate\Support\Facades\Auth;

class RespondentController extends Controller
{
    protected $respondentService;

    public function __construct(\App\Services\Dashboard\RespondentService $respondentService)
    {
        $this->respondentService = $respondentService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        $filters = $request->only(['search', 'village_id', 'gender']);
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        $respondents = $this->respondentService->getPaginatedRespondents($user, $filters, $sort, $direction);

        $villages = \App\Models\Village::orderBy('name')->get();
        
        $posyandusQuery = \App\Models\HealthPost::orderBy('name');
        if ($user->isAdminPosyandu()) {
            $posyandusQuery->where('id', $user->health_post_id);
        }
        $posyandus = $posyandusQuery->get();

        return view('pages.dashboard.responden.index', compact('respondents', 'villages', 'posyandus'));
    }

    public function show($id)
    {
        $respondent = Respondent::findOrFail($id);
        $this->authorize('view', $respondent);
        
        // Eager load relations for view
        $respondent->load(['device', 'village', 'healthPost']);

        return view('pages.dashboard.responden.show', compact('respondent'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16|unique:respondents',
            'fullname' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'village_id' => 'required|exists:villages,id',
            'health_post_id' => 'required|exists:health_posts,id',
        ]);

        Respondent::create($request->all());

        return back()->with('success', 'Responden berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $respondent = Respondent::findOrFail($id);
        $this->authorize('update', $respondent);

        $request->validate([
            'nik' => 'required|string|size:16|unique:respondents,nik,'.$id,
            'fullname' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'village_id' => 'required|exists:villages,id',
            'health_post_id' => 'required|exists:health_posts,id',
        ]);

        $respondent->update($request->all());

        return back()->with('success', 'Data responden berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $respondent = Respondent::findOrFail($id);
        $this->authorize('delete', $respondent);
        
        // Optionally prevent deleting if they have screenings?
        // But for this CRUD, we will delete it (and let DB cascade or we force it)
        $respondent->delete();

        return redirect()->route('responden.index')->with('success', 'Data responden berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $filters = $request->only(['search', 'village_id', 'gender']);
        
        // Reuse service logic but get all without pagination
        $query = Respondent::filterByRole($user)->with('healthPost', 'village');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('fullname', 'like', "%{$search}%");
            });
        }
        if (!empty($filters['village_id'])) {
            $query->where('village_id', $filters['village_id']);
        }
        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        $respondents = $query->orderBy('fullname', 'asc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=Data_Responden.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($respondents) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'NIK', 'Nama Lengkap', 'Jenis Kelamin', 'Tanggal Lahir', 'Alamat', 'Desa', 'Posyandu'], ';');

            foreach ($respondents as $index => $r) {
                fputcsv($file, [
                    $index + 1,
                    "'" . $r->nik,
                    $r->fullname,
                    $r->gender == 'L' ? 'Laki-laki' : 'Perempuan',
                    $r->birth_date,
                    $r->address,
                    $r->village->name ?? '-',
                    $r->healthPost->name ?? '-',
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
