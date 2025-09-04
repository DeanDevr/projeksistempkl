@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Riwayat Pengajuan PKL</h1>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($pengajuans->isEmpty())
        <p class="text-gray-600 dark:text-gray-300">Belum ada pengajuan PKL yang dikirim.</p>
    @else
        <table class="min-w-full border rounded-lg overflow-hidden">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white">
                <tr>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Sekolah</th>
                    <th class="px-4 py-2 text-left">Jurusan</th>
                    <th class="px-4 py-2 text-left">Tanggal PKL</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Surat</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                @foreach ($pengajuans as $pengajuan)
                    <tr class="border-t dark:border-gray-600">
                        <td class="px-4 py-2 font-semibold">{{ $pengajuan->nama }}</td>
                        <td class="px-4 py-2">{{ $pengajuan->asal_sekolah }}</td>
                        <td class="px-4 py-2">{{ $pengajuan->jurusan }}</td>
                        <td class="px-4 py-2">
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-2">
                            @if ($pengajuan->status === 'diterima')
                                <span class="text-green-600 font-semibold">Diterima</span>
                            @elseif ($pengajuan->status === 'ditolak')
                                <span class="text-red-600 font-semibold">Ditolak</span>
                            @else
                                <span class="text-yellow-600 font-semibold">Diproses</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if ($pengajuan->surat_pengantar)
                                <a href="{{ asset('storage/' . $pengajuan->surat_pengantar) }}" class="text-blue-500 hover:underline" target="_blank">Lihat Surat</a>
                            @else
                                <span class="text-gray-400">Tidak ada</span>
                            @endif
                        </td>
                    </tr>

                 
{{-- Tampilkan anggota jika jenis kelompok --}}
   @if ($pengajuan->jenis_pengajuan === 'kelompok' && $pengajuan->anggota->count())
<tr class="bg-gray-50 dark:bg-gray-900 border-t dark:border-gray-600">
    <td colspan="6" class="px-4 py-3">
        <div class="text-sm font-semibold text-gray-800 dark:text-white mb-1">Anggota Kelompok:</div>
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
            <thead>
                <tr>
                    <th class="px-2 py-1">Nama</th>
                    <th class="px-2 py-1">NISN</th>
                    <th class="px-2 py-1">Sekolah</th>
                    <th class="px-2 py-1">Jurusan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengajuan->anggota as $anggota)
                <tr>
                    <td class="px-2 py-1">{{ $anggota->nama }}</td>
                    <td class="px-2 py-1">{{ $anggota->nisn }}</td>
                    <td class="px-2 py-1">{{ $anggota->asal_sekolah }}</td>
                    <td class="px-2 py-1">{{ $anggota->jurusan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </td>
</tr>
@endif
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $pengajuans->links() }}
        </div>
    @endif
</div>
@endsection