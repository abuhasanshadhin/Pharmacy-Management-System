@props([
    'label' => '',
    'position' => 'start',
    'variant' => 'primary',
])

<div class="clearfix mt-3">
    <div class="float-{{$position}}">
        <button {{ $attributes->class(['btn',"btn-$variant"]) }}>
            <span>{{ $label }} <i class="bi bi-save2"></i></span>
        </button>
    </div>
</div>
