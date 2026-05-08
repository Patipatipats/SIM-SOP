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

        // Inisialisasi variabel agar tidak error di view
        $chartData = [];

        // ================= 1. STATISTIK UMUM =================
        if (in_array($roleId, [1, 2])) {
            $totalSop      = DB::table('sop')->count();
            $sopAktif      = DB::table('sop')->where('is_active', 1)->count();
            $totalKategori = DB::table('kategori_sop')->count();
            $userAktif     = DB::table('profil_pengguna')->where('is_aktif', 1)->count();
            
            $sopTerbaru = DB::table('sop')->orderBy('created_at', 'desc')->limit(5)->get();

            // 🔥 AMBIL DATA UNTUK GRAFIK (Hanya untuk Admin/SuperAdmin)
            $chartData = DB::table('sop')
            ->join('kategori_sop', 'sop.kategori_id', '=', 'kategori_sop.id') // <-- Ganti di sini jing!
            ->select('kategori_sop.nama_kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori_sop.nama_kategori', 'kategori_sop.id')
            ->get();

        } elseif ($roleId == 3) {
            $totalSop      = DB::table('sop')->where('unit_kerja_id', $user->unit_kerja_id)->count();
            $sopAktif      = DB::table('sop')->where('unit_kerja_id', $user->unit_kerja_id)->where('is_active', 1)->count();
            $totalKategori = DB::table('kategori_sop')->count();
            $userAktif     = DB::table('profil_pengguna')->where('unit_kerja_id', $user->unit_kerja_id)->count();
            
            $sopTerbaru = DB::table('sop')->where('unit_kerja_id', $user->unit_kerja_id)->orderBy('created_at', 'desc')->limit(5)->get();

        } elseif ($roleId == 4) {
            $totalSop      = DB::table('sop')->count();
            $sopAktif      = DB::table('sop')->where('status', 'Review')->count();
            $totalKategori = DB::table('kategori_sop')->count();
            $userAktif     = DB::table('profil_pengguna')->count();
            
            $sopTerbaru = DB::table('sop')->where('status', 'Review')->orderBy('updated_at', 'desc')->limit(5)->get();
        } else {
            // Default untuk role lain (User Internal/Guest)
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

        // ================= 3. KIRIM KE VIEW =================
        return view('dashboard', compact(
            'totalSop',
            'sopAktif',
            'totalKategori',
            'userAktif',
            'sopTerbaru',
            'logAktivitas',
            'chartData' // 🔥 PASTIKAN INI ADA
        ));
    }
}