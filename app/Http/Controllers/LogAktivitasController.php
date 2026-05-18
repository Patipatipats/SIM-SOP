<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data User buat dropdown Filter (Pake USERNAME biar kaga error kolom)
        $users = DB::table('users')->select('id', 'username as nama')->orderBy('username', 'asc')->get();

        // ==========================================
        // QUERY 1: LOG AKTIVITAS UMUM
        // ==========================================
        $queryAktivitas = DB::table('log_aktivitas')
            ->leftJoin('users', 'log_aktivitas.user_id', '=', 'users.id')
            ->select('log_aktivitas.*', 'users.username as nama_user'); 

        if ($request->filled('user_id')) { $queryAktivitas->where('log_aktivitas.user_id', $request->user_id); }
        if ($request->filled('modul')) { $queryAktivitas->where('log_aktivitas.modul', $request->modul); }
        if ($request->filled('aktivitas')) { $queryAktivitas->where('log_aktivitas.aktivitas', 'like', '%' . $request->aktivitas . '%'); }
        if ($request->filled('tanggal')) { $queryAktivitas->whereDate('log_aktivitas.created_at', $request->tanggal); }

        $logAktivitas = $queryAktivitas->orderBy('log_aktivitas.id', 'desc')->paginate(10, ['*'], 'page_aktivitas');


        // ==========================================
        // QUERY 2: LOG VIEW SOP
        // ==========================================
        $queryView = DB::table('sop_views')
            ->leftJoin('users', 'sop_views.user_id', '=', 'users.id')
            ->leftJoin('sop', 'sop_views.sop_id', '=', 'sop.id')
            ->select('sop_views.*', 'users.username as nama_user', 'sop.kode_sop', 'sop.judul'); 

        if ($request->filled('user_id')) { $queryView->where('sop_views.user_id', $request->user_id); }
        if ($request->filled('tanggal')) { $queryView->whereDate('sop_views.viewed_at', $request->tanggal); }

        $logViews = $queryView->orderBy('sop_views.id', 'desc')->paginate(10, ['*'], 'page_view');


        // ==========================================
        // QUERY 3: LOG DOWNLOAD SOP
        // ==========================================
        $queryDownload = DB::table('sop_download_logs')
            ->leftJoin('users', 'sop_download_logs.user_id', '=', 'users.id')
            ->leftJoin('sop', 'sop_download_logs.sop_id', '=', 'sop.id')
            ->leftJoin('sop_versions', 'sop_download_logs.version_id', '=', 'sop_versions.id')
            ->select('sop_download_logs.*', 'users.username as nama_user', 'sop.kode_sop', 'sop.judul', 'sop_versions.versi'); 

        if ($request->filled('user_id')) { $queryDownload->where('sop_download_logs.user_id', $request->user_id); }
        if ($request->filled('tanggal')) { $queryDownload->whereDate('sop_download_logs.downloaded_at', $request->tanggal); }

        $logDownloads = $queryDownload->orderBy('sop_download_logs.id', 'desc')->paginate(10, ['*'], 'page_download');


        // ==========================================
        // QUERY 4: LOG APPROVAL SOP
        // ==========================================
        $queryApproval = DB::table('sop_approvals')
            ->leftJoin('users', 'sop_approvals.user_id', '=', 'users.id')
            ->leftJoin('sop_versions', 'sop_approvals.version_id', '=', 'sop_versions.id')
            ->leftJoin('sop', 'sop_versions.sop_id', '=', 'sop.id')
            ->select('sop_approvals.*', 'users.username as nama_user', 'sop.kode_sop', 'sop.judul', 'sop_versions.versi'); 

        if ($request->filled('user_id')) { $queryApproval->where('sop_approvals.user_id', $request->user_id); }
        if ($request->filled('tanggal')) { $queryApproval->whereDate('sop_approvals.created_at', $request->tanggal); }

        $logApprovals = $queryApproval->orderBy('sop_approvals.id', 'desc')->paginate(10, ['*'], 'page_approval');


        return view('manajemen_laporan.logaktivitas.index', compact('users', 'logAktivitas', 'logViews', 'logDownloads', 'logApprovals'));
    }
}