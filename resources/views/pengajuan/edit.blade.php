@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Pengajuan PKL</h1>

    <form action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200">Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $pengajuan->nama) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="">NISN</label>
            <input type="text" name="nisn" value="{{ old('nisn', $pengajuan->nisn) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200">Jurusan</label>
            <input type="text" name="jurusan" value="{{ old('jurusan', $pengajuan->jurusan) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200">Asal Sekolah</label>
            <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah', $pengajuan->asal_sekolah) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $pengajuan->tanggal_mulai->format('Y-m-d')) }}" class="ww-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $pengajuan->tanggal_selesai->format('Y-m-d')) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200">Surat Pengantar (PDF, max 2MB)</label>
            <input type="file" name="surat_pengantar" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
            @if ($pengajuan->surat_pengantar)
                <p class="text-sm mt-2">File saat ini: 
                    <a href="{{ asset('storage/' . $pengajuan->surat_pengantar) }}" target="_blank" class="text-blue-600 underline">Lihat</a>
                </p>
            @endif
        </div>

@if ($pengajuan->jenis_pengajuan === 'kelompok')
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mt-6 mb-2">Anggota Kelompok</h3>
    <div id="anggota-wrapper" class="space-y-4">
        @foreach ($pengajuan->anggota as $index => $anggota)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-900 p-4 rounded">
                <input type="hidden" name="anggota[{{ $index }}][id]" value="{{ $anggota->id }}">

                <div>
                    <label class="block text-sm font-medium">Nama</label>
                    <input type="text" name="anggota[{{ $index }}][nama]" value="{{ $anggota->nama }}" class="form-input w-full mb-2 p-2 border rounded bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium">NISN</label>
                    <input type="text" name="anggota[{{ $index }}][nisn]" value="{{ $anggota->nisn }}" class="form-input w-full mb-2 p-2 border rounded bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium">Asal Sekolah</label>
                    <input type="text" name="anggota[{{ $index }}][asal_sekolah]" value="{{ $anggota->asal_sekolah }}" class="form-input w-full mb-2 p-2 border rounded bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium">Jurusan</label>
                    <input type="text" name="anggota[{{ $index }}][jurusan]" value="{{ $anggota->jurusan }}" class="form-input w-full mb-2 p-2 border rounded bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                </div>
            </div>
        @endforeach
    </div>
@endif

        <div class="flex justify-end">
            <a href="{{ route('pengajuan.index') }}" class="mr-4 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection