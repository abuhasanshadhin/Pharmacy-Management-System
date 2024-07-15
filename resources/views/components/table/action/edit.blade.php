@props([
    'route' => null,
    'id' => 'edit-specific-row',
    'text' => '',
    'icon' => 'bi bi-pencil'
])

<a id="{{ $id }}" href="{{ $route }}" {{ $attributes->merge(['class' => 'btn btn-sm btn-outline-warning']) }}>
    <span>{{ translator($text) }}</span>
    <i class="{{ $icon }}"></i>
</a>
