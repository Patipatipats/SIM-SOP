<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roleId = $user->role_id;

        // Inisialisasi variabel default agar tidak error di view
        $chartData = [];
        $sopDraft = 0;
        $sopReview = 0;
        $sopDitolak = 0;
        $totalUnit = DB::table('unit_kerja')->count();

        // ================= 1. STATISTIK UTAMA & STATUS (TAHAP 8) =================
        if (in_array($roleId, [1, 2])) {
            // Admin / SuperAdmin: Liat Semua
            $totalSop      = DB::table('sop')->count();
            $sopAktif      = DB::table('sop')->where('status', 'Active')->count();
            $sopDraft      = DB::table('sop')->where('status', 'Draft')->count();
            $sopReview     = DB::table('sop')->where('status', 'Review')->count();
            $sopDitolak    = DB::table('sop')->where('status', 'Rejected')->count();
            $totalKategori = DB::table('kategori_sop')->count();
            $userAktif     = DB::table('profil_pengguna')->where('is_aktif', 1)->count();
            
            $sopTerbaru = DB::table('sop')->orderBy('created_at', 'desc')->limit(5)->get();

            // DATA GRAFIK
            $chartData = DB::table('sop')
                ->join('kategori_sop', 'sop.kategori_id', '=', 'kategori_sop.id')
                ->select('kategori_sop.nama_kategori', DB::raw('count(*) as total'))
                ->groupBy('kategori_sop.nama_kategori', 'kategori_sop.id')
                ->get();

        } elseif ($roleId == 3 || $roleId == 6) {
            // Admin Unit / Operator: Filter berdasarkan Unit Kerja mereka
            $totalSop      = DB::table('sop')->where('unit_kerja_id', $user->unit_kerja_id)->count();
            $sopAktif      = DB::table('sop')->where('unit_kerja_id', $user->unit_kerja_id)->where('status', 'Active')->count();
            $sopDraft      = DB::table('sop')->where('unit_kerja_id', $user->unit_kerja_id)->where('status', 'Draft')->count();
            $sopReview     = DB::table('sop')->where('unit_kerja_id', $user->unit_kerja_id)->where('status', 'Review')->count();
            $sopDitolak    = DB::table('sop')->where('unit_kerja_id', $user->unit_kerja_id)->where('status', 'Rejected')->count();
            $totalKategori = DB::table('kategori_sop')->count();
            $userAktif     = DB::table('profil_pengguna')->where('unit_kerja_id', $user->unit_kerja_id)->count();
            
            $sopTerbaru = DB::table('sop')->where('unit_kerja_id', $user->unit_kerja_id)->orderBy('created_at', 'desc')->limit(5)->get();

        } elseif ($roleId == 4) {
            // Approver: Fokus ke yang status 'Review' (Pending)
            $totalSop      = DB::table('sop')->count();
            $sopAktif      = DB::table('sop')->where('status', 'Active')->count();
            $sopReview     = DB::table('sop')->where('status', 'Review')->count();
            $sopDitolak    = DB::table('sop')->where('status', 'Rejected')->count();
            $totalKategori = DB::table('kategori_sop')->count();
            $userAktif     = DB::table('profil_pengguna')->count();
            
            $sopTerbaru = DB::table('sop')->where('status', 'Review')->orderBy('updated_at', 'desc')->limit(5)->get();
        } else {
            // Default User Biasa
            $totalSop      = DB::table('sop')->where('status', 'Active')->count();
            $sopAktif      = $totalSop;
            $totalKategori = DB::table('kategori_sop')->count();
            $userAktif     = 1;
            $sopTerbaru    = DB::table('sop')->where('status', 'Active')->limit(5)->get();
        }

        // ================= 2. LOG AKTIVITAS =================
        $logQuery = DB::table('log_aktivitas');
        if ($roleId != 1) {
            $logQuery->where('user_id', $user->id);
        }
        $logAktivitas = $logQuery->orderBy('created_at', 'desc')->limit(5)->get();

        // ================= 3. KIRIM SEMUA KE VIEW =================
        return view('dashboard', compact(
            'totalSop',
            'sopAktif',
            'sopDraft',
            'sopReview',
            'sopDitolak',
            'totalKategori',
            'totalUnit',
            'userAktif',
            'sopTerbaru',
            'logAktivitas',
            'chartData'
        ));
    }
}