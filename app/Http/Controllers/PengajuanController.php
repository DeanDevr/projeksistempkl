<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\AnggotaPengajuan;

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = Pengajuan::with('anggota')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(1);

        return view('pengajuan.index', compact('pengajuans'));
    }

    public function create()
    {
        return view('pengajuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_pengajuan' => 'required|in:sendiri,kelompok',
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:20',
            'asal_sekolah' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'surat_pengantar' => 'nullable|file|mimes:pdf|max:2048',
        ]);

 
        $filtered = [];
        if ($request->jenis_pengajuan === 'kelompok') {
            $anggota = $request->input('anggota', []);

            $filtered = array_filter($anggota, function ($item) {
                return !empty($item['nama']) || !empty($item['nisn']) || !empty($item['asal_sekolah']) || !empty($item['jurusan']);
            });

            if (count($filtered) < 1) {
                return back()->withErrors(['anggota' => 'Minimal 1 anggota kelompok harus diisi lengkap.'])->withInput();
            }

            foreach ($filtered as $index => $item) {
                $request->validate([
                    "anggota.$index.nama" => 'required|string',
                    "anggota.$index.nisn" => 'required|string',
                    "anggota.$index.asal_sekolah" => 'required|string',
                    "anggota.$index.jurusan" => 'required|string',
                ]);
            }
        }

        $pengajuan = Pengajuan::create([
            'user_id' => Auth::id(),
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'asal_sekolah' => $request->asal_sekolah,
            'jurusan' => $request->jurusan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'surat_pengantar' => $request->file('surat_pengantar') ? $request->file('surat_pengantar')->store('surat_pengantar', 'public') : null,
            'jenis_pengajuan' => $request->jenis_pengajuan,
        ]);

        if ($request->jenis_pengajuan === 'kelompok') {
            foreach ($filtered as $anggota) {
                $pengajuan->anggota()->create([
                    'nama' => $anggota['nama'],
                    'nisn' => $anggota['nisn'],
                    'asal_sekolah' => $anggota['asal_sekolah'],
                    'jurusan' => $anggota['jurusan'],
                ]);
            }
        }

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dikirim.');
    }

    public function edit($id)
    {
        $pengajuan = Pengajuan::with('anggota')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pengajuan.edit', compact('pengajuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:20',
            'jurusan' => 'required|string|max:100',
            'asal_sekolah' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'surat_pengantar' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $pengajuan = Pengajuan::with('anggota')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $data = $request->only([
            'nama',
            'nisn',
            'jurusan',
            'asal_sekolah',
            'tanggal_mulai',
            'tanggal_selesai',
        ]);

        if ($request->hasFile('surat_pengantar')) {
            if ($pengajuan->surat_pengantar) {
                Storage::disk('public')->delete($pengajuan->surat_pengantar);
            }
            $data['surat_pengantar'] = $request->file('surat_pengantar')->store('surat_pengantar', 'public');
        }

        $pengajuan->update($data);

        // Update anggota kelompok
        if ($pengajuan->jenis_pengajuan === 'kelompok' && $request->has('anggota')) {
            foreach ($request->anggota as $anggotaInput) {
                if (!empty($anggotaInput['id'])) {
                    $anggota = $pengajuan->anggota()->find($anggotaInput['id']);
                    if ($anggota) {
                        $anggota->update([
                            'nama' => $anggotaInput['nama'],
                            'nisn' => $anggotaInput['nisn'],
                            'asal_sekolah' => $anggotaInput['asal_sekolah'],
                            'jurusan' => $anggotaInput['jurusan'],
                        ]);
                    }
                }
            }
        }

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengajuan = Pengajuan::where('user_id', Auth::id())->findOrFail($id);

        if ($pengajuan->surat_pengantar) {
            Storage::disk('public')->delete($pengajuan->surat_pengantar);
        }

        $pengajuan->delete();

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function status()
    {
        $pengajuans = Pengajuan::with('anggota')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pengajuan.status', compact('pengajuans'));
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diproses,diterima,ditolak',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->save();

        return back()->with('success', 'Status pengajuan diperbarui.');
    }
}