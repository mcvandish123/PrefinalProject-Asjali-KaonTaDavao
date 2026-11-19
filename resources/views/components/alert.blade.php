@props(['type' => 'success'])

<div {{ $attributes->class([
    'mb-4 rounded border px-4 py-3 text-sm',
    'border-green-300 bg-green-50 text-green-800' => $type === 'success',
    'border-red-300 bg-red-50 text-red-800' => $type === 'error',
]) }}>
    {{ $slot }}
</div>
