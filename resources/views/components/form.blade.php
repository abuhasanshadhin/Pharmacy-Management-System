@props([
    'method' => 'POST',
    'inputsInline' => false,
])

@php($method = strtoupper($method))

<form
    {{ $attributes }}
    method="{{ $method == 'GET' ? 'GET' : 'POST' }}"
    enctype="multipart/form-data"
>
    @csrf
    @if (!in_array($method, ['GET', 'POST']))
        @method($method)
    @endif
    {{ $slot }}
</form>
