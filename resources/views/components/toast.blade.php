@props([
'type' => 'success', // success, error, warning
'message' => '',
'duration' => 5000
])

@php
$iconClasses = [
'success' => 'text-green-500',
'error' => 'text-red-500',
'warning' => 'text-orange-500',
];

$bgClasses = [
'success' => 'bg-green-500/20',
'error' => 'bg-red-500/20',
'warning' => 'bg-orange-500/20',
];

$iconPaths = [
'success' => 'M5 13l4 4L19 7', // check
'error' => 'M6 18L18 6M6 6l12 12', // x
'warning' => 'M12 9v4m0 4h.01', // !
];

$iconClass = $iconClasses[$type] ?? $iconClasses['success'];
$bgClass = $bgClasses[$type] ?? $bgClasses['success'];
$iconPath = $iconPaths[$type] ?? $iconPaths['success'];
@endphp
<div
    x-data="{ show: true }"
    x-show="show"
    x-transition.opacity.duration.300
    x-init="setTimeout(() => show = false, {{ $duration }} )"
    x-cloak
    class="fixed bottom-4 right-4 flex items-center w-full max-w-sm p-4 rounded-xl shadow-md bg-white/90">

    <!-- Icon -->
    <div class="shrink-0 w-10 h-10 mr-3 flex items-center justify-center rounded-full {{ $bgClass }}">
        <svg class="w-6 h-6 {{ $iconClass }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
        </svg>
    </div>

    <!-- Pesan -->
    <div class="flex-1 text-sm text-gray-900">
        {{ $message }}
    </div>

    <!-- Tombol tutup -->
    <button @click="show = false" class="ml-4 text-gray-400 hover:text-gray-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>