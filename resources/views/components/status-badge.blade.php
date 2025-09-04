<div>
@props(['status'])

@php
    $badgeClass = match($status) {
        'Diproses' => 'bg-yellow-400 text-black dark:bg-yellow-600 dark:text-white',
        'Diterima' => 'bg-green-400 text-black dark:bg-green-600 dark:text-white',
        'Ditolak'  => 'bg-red-400 text-black dark:bg-red-600 dark:text-white',
        default    => 'bg-gray-300 text-black dark:bg-gray-600 dark:text-white',
    };
@endphp

<span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
    {{ $status }}
</span>
</div>