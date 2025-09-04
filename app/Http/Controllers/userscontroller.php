<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;   
use Illuminate\Support\Facades\Auth;
use App\Models\Pengajuan;

class userscontroller extends Controller
{
      public function index()
{

$userId = Auth::id();

$pengajuanBerkelompok = Pengajuan::where('user_id', $userId)
    ->where('jenis_pengajuan', 'kelompok')
    ->latest()
    ->get();

$pengajuanIndividu = Pengajuan::where('user_id', $userId)
    ->where('jenis_pengajuan', 'sendiri')
    ->latest()
    ->get();

// TOTAL SEMUA PENGAJUAN (untuk semua user)
$totalPengajuan = Pengajuan::count();
$totalDisetujui = Pengajuan::where('status', 'disetujui')->count();
$totalDitolak   = Pengajuan::where('status', 'ditolak')->count();



    return view('dashboard', compact(
        'totalPengajuan',
        'totalDisetujui',
        'totalDitolak',
        'pengajuanBerkelompok',
        'pengajuanIndividu'
    ));
}
}
