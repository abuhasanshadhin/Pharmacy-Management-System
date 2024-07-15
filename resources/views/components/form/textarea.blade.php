@props([
    'label' => '',
    'name' => '',
    'id' => uniqid(),
    'value' => '',
    'labelClass' => '',
    'inputClass' => '',
    'placeholder' => '',
    'inputAttrs' => [],
    'required' => false,
    'labelColumn' => 'col-md-3',
    'inputColumn' => 'col-md-9',
])

@aware(['inputsInline' => false])

@php($dotsName = CH::htmlInputNameArrayToDots($name))

<div {{ $attributes->class(['mb-3', 'row' => $inputsInline]) }}>
    @if ($label)
        <label
            for="{{ $id }}"
            @class(['form-label', $labelColumn => $inputsInline, $labelClass])
        >
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    <div @class([$inputColumn => $inputsInline])>
        <textarea
            id="{{ $id }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            class="form-control {{ $inputClass }}"
            {!! CH::htmlTagAttrsArrayToString($inputAttrs) !!}
        >{{ old($dotsName, $value) }}</textarea>
        @error($dotsName)
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
</div>
