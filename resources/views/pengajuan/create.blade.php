@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Form Pengajuan PKL</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 dark:bg-red-200 dark:text-red-800">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        @csrf

        <!-- Jenis Pengajuan -->
        <div class="mb-6">
            <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Jenis Pengajuan</label>
            <div class="flex space-x-6">
                <label class="flex items-center space-x-2">
                    <input type="radio" name="jenis_pengajuan" value="sendiri"
                        {{ old('jenis_pengajuan', 'sendiri') == 'sendiri' ? 'checked' : '' }}
                        onclick="toggleAnggota(false)">
                    <span class="text-gray-800 dark:text-gray-200">Sendiri</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="radio" name="jenis_pengajuan" value="kelompok"
                        {{ old('jenis_pengajuan') == 'kelompok' ? 'checked' : '' }}
                        onclick="toggleAnggota(true)">
                    <span class="text-gray-800 dark:text-gray-200">Kelompok</span>
                </label>
            </div>
        </div>

        <!-- Data Diri Utama -->
        <div class="mb-4">
            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                class="w-full p-2 border rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>

        <div class="mb-4">
            <label for="nisn" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NISN</label>
            <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}"
                class="w-full p-2 border rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>

        <div class="mb-4">
            <label for="asal_sekolah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Asal Sekolah</label>
            <input type="text" name="asal_sekolah" id="asal_sekolah" value="{{ old('asal_sekolah') }}"
                class="w-full p-2 border rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>

        <div class="mb-4">
            <label for="jurusan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jurusan</label>
            <input type="text" name="jurusan" id="jurusan" value="{{ old('jurusan') }}"
                class="w-full p-2 border rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>

        <div class="mb-4">
            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                class="w-full p-2 border rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>

        <div class="mb-4">
            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                class="w-full p-2 border rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>

        <div class="mb-4">
            <label for="surat_pengantar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Surat Pengantar (PDF)</label>
            <input type="file" name="surat_pengantar" id="surat_pengantar" accept="application/pdf"
                class="w-full p-2 border rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>

        <!-- Anggota Kelompok -->
        <div id="anggota-kelompok-section" class="mb-4 hidden">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Anggota Kelompok</h3>
            <div id="anggota-wrapper">
                {{-- Kosong saat awal --}}
            </div>
            <button type="button" id="add-anggota" class="mt-2 text-sm text-blue-500 hover:underline">+ Tambah Anggota</button>
        </div>

        <div class="mt-6">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Kirim Pengajuan</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const anggotaSection = document.getElementById('anggota-kelompok-section');
        const anggotaWrapper = document.getElementById('anggota-wrapper');
        const addAnggotaBtn = document.getElementById('add-anggota');

        function toggleAnggota(show) {
            if (show) {
                anggotaSection.classList.remove('hidden');

                if (anggotaWrapper.children.length === 0) {
                    addAnggotaForm();
                }
            } else {
                anggotaSection.classList.add('hidden');
                anggotaWrapper.innerHTML = '';
            }
        }

    let anggotaIndex = 0;

function addAnggotaForm() {
    const anggotaHTML = `
        <div class="mb-4 border p-3 rounded bg-gray-50 dark:bg-gray-700">
            <input type="text" name="anggota[${anggotaIndex}][nama]" placeholder="Nama Anggota" required
                class="w-full mb-2 p-2 border rounded bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
            <input type="text" name="anggota[${anggotaIndex}][nisn]" placeholder="NISN" required
                class="w-full mb-2 p-2 border rounded bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
            <input type="text" name="anggota[${anggotaIndex}][asal_sekolah]" placeholder="Asal Sekolah" required
                class="w-full mb-2 p-2 border rounded bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
            <input type="text" name="anggota[${anggotaIndex}][jurusan]" placeholder="Jurusan" required
                class="w-full p-2 border rounded bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
        </div>
    `;
    anggotaWrapper.insertAdjacentHTML('beforeend', anggotaHTML);
    anggotaIndex++;
}

        addAnggotaBtn.addEventListener('click', addAnggotaForm);

        // ⛳️ PANGGIL toggle setelah semua fungsi dideklarasikan
        const selected = document.querySelector('input[name="jenis_pengajuan"]:checked');
        toggleAnggota(selected && selected.value === 'kelompok');
        
        // Global agar bisa dipanggil dari onclick di HTML
        window.toggleAnggota = toggleAnggota;
    });
</script>
@endsection