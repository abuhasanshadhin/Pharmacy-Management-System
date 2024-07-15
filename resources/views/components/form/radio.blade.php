@props([
    'name' => '',
    'label' => '',
    'options' => [],
    'class' => 'col-lg-4',
    'checked' => 'active',
    'labelColumn' => 'col-md-3',
    'inputColumn' => 'col-md-6',
])
@aware(['inputsInline' => false])

    <div {{ $attributes->class(['mb-3 align-items-center','row' => $inputsInline, $class]) }}>
        <label for="" class="{{ $labelColumn }}">{{ translator($label) }}</label>
        <div class="{{$inputColumn}} d-flex justify-content-between {{ !$inputsInline ? 'mt-3':'' }}" >
            @foreach($options as $option)
                @php $value = str_replace(' ','_',$option) @endphp
                <div class="form-check">
                    <input class="form-check-input"
                           @if(strtolower($checked) == strtolower($value)) checked @endif
                           type="radio"
                           name="{{$name}}"
                           id="{{ strtolower($option) }}"
                           value="{{ strtolower($value) }}"
                    >
                    <label class="form-check-label" for="{{ strtolower($option) }}">
                        {{ translator($option) }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>


