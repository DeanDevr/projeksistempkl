<?php
namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\AnggotaPengajuan;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index($pengajuan_id)
    {
        $pengajuan = Pengajuan::with('anggota')->findOrFail($pengajuan_id);
        return view('anggota.index', compact('pengajuan'));
    }

    public function create($pengajuan_id)
    {
    
        return view('anggota.create', compact('pengajuan_id'));
    }

    public function store(Request $request, $pengajuan_id)
    {
        $request->validate([
            'nama' => 'required',
            'nisn' => 'required',
            'asal_sekolah' => 'required',
            'jurusan' => 'required',
        ]);

        AnggotaPengajuan::create([
            'pengajuan_id' => $pengajuan_id,
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'asal_sekolah' => $request->asal_sekolah,
            'jurusan' => $request->jurusan,
        ]);

        return redirect()->route('anggota.index', $pengajuan_id)->with('success', 'Anggota ditambahkan.');
    }

    public function edit($id)
    {
        $anggota = AnggotaPengajuan::findOrFail($id);
        return view('anggota.edit', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'nisn' => 'required',
            'asal_sekolah' => 'required',
            'jurusan' => 'required',
            
        ]);

        $anggota = AnggotaPengajuan::findOrFail($id);
        $anggota->update($request->only(['nama', 'nisn', 'asal_sekolah', 'jurusan']));

        return redirect()->route('anggota.index', $anggota->pengajuan_id)->with('success', 'Anggota diperbarui.');
    }

    public function destroy($id)
    {
        $anggota = AnggotaPengajuan::findOrFail($id);
        $pengajuan_id = $anggota->pengajuan_id;
        $anggota->delete();

        return redirect()->route('anggota.index', $pengajuan_id)->with('success', 'Anggota dihapus.');
    }
}