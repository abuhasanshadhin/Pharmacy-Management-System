@props([
    'type' => 'text',
    'label' => '',
    'name' => '',
    'id' => uniqid(),
    'value' => '',
    'labelClass' => '',
    'inputClass' => '',
    'placeholder' => 'Enter here',
    'inputAttrs' => [],
    'required' => false,
    'browserRequired' => false,
    'labelColumn' => 'col-md-3 pe-0',
    'inputColumn' => 'col-md-9',
])

@aware(['inputsInline' => false])

@php
    $dotsName = CH::htmlInputNameArrayToDots($name);
@endphp

<div {{ $attributes->class(['mb-3']) }}>
    <div class="{{ $inputsInline ? 'row align-items-center':'' }}">
        @if ($label)
            <label
                for="{{ $id }}"
                @class(['form-label mb-0', $labelColumn => $inputsInline, $labelClass])
            >
            {{ translator($label) }}
            @if ($required)
                <span class="text-danger">*</span>
                @endif
                </label>
            @endif
            <div @class([$inputColumn => $inputsInline])>
                <input
                    {{ $browserRequired ? 'required':'' }}
                    id="{{ $id }}"
                    type="{{ $type }}"
                    name="{{ $name }}"
                    value="{{ old($dotsName, $value) }}"
                    placeholder="{{ translator($placeholder) }}"
                    class="form-control {{ $inputClass }}"
                    {!! CH::htmlTagAttrsArrayToString($inputAttrs) !!}
                />
                @error($dotsName)
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
    </div>

</div>
