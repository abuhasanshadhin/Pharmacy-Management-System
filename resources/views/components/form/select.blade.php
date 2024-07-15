@props([
    'label' => '',
    'name' => '',
    'id' => uniqid(),
    'selected' => '',
    'labelClass' => '',
    'inputClass' => '',
    'inputAttrs' => [],
    'required' => false,
    'labelColumn' => 'col-md-3 pe-0',
    'inputColumn' => 'col-md-9',
])

@aware(['inputsInline' => false])

<div {{ $attributes->class(['mb-3' => $inputsInline]) }}>
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
                <select
                    id="{{ $id }}"
                    name="{{ $name }}"
                    class="form-select {{ $inputClass }}"
                    {!! CH::htmlTagAttrsArrayToString($inputAttrs) !!}
                >
                    <option value="">―{{ translator('Choose') }}―</option>
                    {{ $slot }}
                </select>
                @error(CH::htmlInputNameArrayToDots($name))
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
    </div>
</div>
