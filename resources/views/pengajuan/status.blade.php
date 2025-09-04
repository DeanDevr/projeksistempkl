@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Status Pengajuan PKL</h1>

    @if ($pengajuans->isEmpty())
        <p class="text-gray-600 dark:text-gray-300">Belum ada pengajuan yang dikirim.</p>
    @else
        <div class="space-y-6">
            @foreach ($pengajuans as $pengajuan)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-700 dark:text-white mb-2">Pengajuan #{{ $loop->iteration }}</h2>

                    <div class="space-y-1 text-sm text-gray-700 dark:text-gray-300">
                        <p><strong>Nama:</strong> {{ $pengajuan->nama }}</p>
                        <p><strong>Sekolah:</strong> {{ $pengajuan->asal_sekolah }}</p>
                        <p><strong>Jurusan:</strong> {{ $pengajuan->jurusan }}</p>
                        <p><strong>Tanggal PKL:</strong> {{ $pengajuan->tanggal_mulai->format('d M Y') }} - {{ $pengajuan->tanggal_selesai->format('d M Y') }}</p>
                        <p><strong>Status:</strong>
                            @if ($pengajuan->status == 'diterima')
                                <span class="text-green-600 font-bold">DITERIMA</span>
                            @elseif ($pengajuan->status == 'ditolak')
                                <span class="text-red-600 font-bold">DITOLAK</span>
                            @else
                                <span class="text-yellow-600 font-bold">SEDANG DIPROSES</span>
                            @endif
                        </p>
                    </div>

                    @if ($pengajuan->jenis_pengajuan === 'kelompok' && $pengajuan->anggota->count())
                        <div class="mt-6">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-2">Anggota Kelompok:</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm text-left border border-gray-300 dark:border-gray-600 rounded-lg">
                                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                        <tr>
                                            <th class="px-3 py-2">Nama</th>
                                            <th class="px-3 py-2">NISN</th>
                                            <th class="px-3 py-2">Sekolah</th>
                                            <th class="px-3 py-2">Jurusan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-700 dark:text-gray-300">
                                        @foreach ($pengajuan->anggota as $anggota)
                                            <tr class="border-t dark:border-gray-600">
                                                <td class="px-3 py-2">{{ $anggota->nama }}</td>
                                                <td class="px-3 py-2">{{ $anggota->nisn }}</td>
                                                <td class="px-3 py-2">{{ $anggota->asal_sekolah }}</td>
                                                <td class="px-3 py-2">{{ $anggota->jurusan }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if ($pengajuan->surat_pengantar)
                        <p class="mt-4">
                            <a href="{{ asset('storage/' . $pengajuan->surat_pengantar) }}" target="_blank" class="text-blue-600 underline">Lihat Surat Pengantar</a>
                        </p>
                    @endif

                    <!-- Tombol Edit & Hapus -->
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('pengajuan.edit', $pengajuan->id) }}" class="text-white bg-blue-600 px-4 py-2 rounded hover:bg-blue-700">Edit</a>
                        <form action="{{ route('pengajuan.destroy', $pengajuan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white bg-red-600 px-4 py-2 rounded hover:bg-red-700">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection