<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ================= STATISTIK =================
        $totalSop      = DB::table('sop')->count();
        $sopAktif      = DB::table('sop')->where('is_active', 1)->count();
        $totalKategori = DB::table('kategori_sop')->count();
        $userAktif     = DB::table('profil_pengguna')->where('is_aktif', 1)->count();

        // ================= DATA TABEL =================
        $sopTerbaru = DB::table('sop')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $logAktivitas = DB::table('log_aktivitas')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ================= KIRIM KE VIEW =================
        return view('dashboard', compact(
            'totalSop',
            'sopAktif',
            'totalKategori',
            'userAktif',
            'sopTerbaru',
            'logAktivitas'
        ));
    }
}