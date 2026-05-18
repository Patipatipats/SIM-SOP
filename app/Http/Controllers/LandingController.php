<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {

        /*
        |--------------------------------------------------------------------------
        | DATA KONTEN LANDING PAGE (DARI CMS ADMIN) 🔥
        |--------------------------------------------------------------------------
        */
        $konten_landing = DB::table('landing_pages')
            ->where('aktif', 1)
            ->orderBy('urutan', 'asc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | KATEGORI SOP + JUMLAH SOP
        |--------------------------------------------------------------------------
        */

        $kategori = DB::table('kategori_sop')
            ->select(
                'kategori_sop.id',
                'kategori_sop.nama_kategori',
                'kategori_sop.slug',
                DB::raw('(
                    SELECT COUNT(*) 
                    FROM sop 
                    WHERE sop.kategori_id = kategori_sop.id
                    AND sop.is_active = 1
                    AND sop.deleted_at IS NULL
                ) as jumlah_sop')
            )
            ->where('kategori_sop.status', 1)
            ->orderBy('kategori_sop.nama_kategori')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SOP TERBARU
        |--------------------------------------------------------------------------
        */

        $sop_terbaru = DB::table('sop')
            ->join('kategori_sop', 'kategori_sop.id', '=', 'sop.kategori_id')
            ->join('unit_kerja', 'unit_kerja.id', '=', 'sop.unit_kerja_id')
            ->select(
                'sop.id',
                'sop.judul',
                'sop.deskripsi',
                'kategori_sop.nama_kategori',
                'unit_kerja.nama_unit',
                'sop.created_at'
            )
            ->where('sop.is_active', 1)
            ->whereNull('sop.deleted_at')
            ->orderByDesc('sop.created_at')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SOP PALING POPULER (BERDASARKAN VIEW)
        |--------------------------------------------------------------------------
        */

        $sop_populer = DB::table('sop')
            ->select(
                'sop.id',
                'sop.judul',
                'sop.total_views' 
            )
            ->where('sop.is_active', 1)
            ->whereNull('sop.deleted_at')
            ->orderByDesc('sop.total_views') 
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SOP UPDATE TERBARU BERDASARKAN VERSI
        |--------------------------------------------------------------------------
        */

        $sop_update_terbaru = DB::table('sop_versions')
            ->join('sop', 'sop.id', '=', 'sop_versions.sop_id')
            ->select(
                'sop.id',
                'sop.judul',
                'sop_versions.versi',
                'sop_versions.created_at'
            )
            ->where('sop.is_active', 1)
            ->whereNull('sop.deleted_at')
            ->orderByDesc('sop_versions.created_at')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $total_sop = DB::table('sop')
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->count();


        $total_unit = DB::table('unit_kerja')
            ->where('status_unit', 1)
            ->count();


        $total_kategori = DB::table('kategori_sop')
            ->where('status', 1)
            ->count();


        $total_download = DB::table('sop_download_logs')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('landing_page', compact(
            'konten_landing', // 🔥 Tambahin ini ke compact
            'kategori',
            'sop_terbaru',
            'sop_populer',
            'sop_update_terbaru',
            'total_sop',
            'total_unit',
            'total_kategori',
            'total_download'
        ));
    }
}