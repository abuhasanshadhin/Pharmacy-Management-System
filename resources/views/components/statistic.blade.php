@props([
    'title' => '',
    'icon' => 'bi bi-currency-dollar',
    'count' => 0,
    'col' => 'col-lg-3',
    'class' => '',
    'variant' => 'variant-1',
])

<div class="{{ $col }} mb-3">
    <div class="statistic shadow-sm {{ $class }} d-flex align-items-center gap-2">
        <div class="icon {{ $variant }}">
            <i class="{{ $icon }}"></i>
        </div>
        <div class="d-flex flex-column">
            <div class="number-count">{{ $count }}</div>
            <div class="title">{{ translator($title) }}</div>
        </div>
    </div>
</div>

