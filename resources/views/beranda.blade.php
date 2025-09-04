@extends('layouts.app')

@section('content')
    <div class="bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Sistem Pengajuan PKL<br class="hidden sm:inline"> Dinas Kominfo Kabupaten Bogor
                </h1>
                <p class="mt-6 text-lg text-gray-600 dark:text-gray-300">
                    Ajukan permohonan magang atau PKL Anda dengan mudah dan cepat melalui sistem kami.
                </p>
                <div class="mt-8 flex justify-center gap-4">
                    <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="inline-block px-6 py-3 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition">
                        Register
                    </a>
                </div>
            </div>
        </div>

        <!-- Extra section: fitur -->
        <div class="bg-gray-100 dark:bg-gray-800 py-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Mudah Digunakan</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Interface sederhana dan ramah pengguna, cocok untuk pelajar.</p>
                </div>
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Proses Cepat</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Pengajuan diproses dengan cepat dan transparan.</p>
                </div>
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Terintegrasi</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Langsung terhubung dengan database instansi terkait.</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-900 py-8 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-gray-600 dark:text-gray-400">© {{ date('Y') }} Diskominfo Kabupaten Bogor. All rights reserved.</p>
            </div>
        </footer>
    </div>
@endsection