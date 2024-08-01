@extends('layout.app')
@php
    $model= 'unit';
    $breadcrumbs = [
            ['text' => 'Medicines'],
            ['text' => 'Units', 'link' => route('unit.index')],
            ['text' => 'Edit Unit']
        ];
@endphp
@section('content')
    <x-container
        title="Unit"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="unit.index"
        btn_title="Back"
    >
        <x-form class="row" :inputsInline="true" action="{{ route('unit.update',$unit->id) }}" method="put">
            <x-form.input
                :required="true"
                class="col-lg-7"
                label="Name"
                name="name"
                value="{{ $unit->name }}"
            ></x-form.input>
            <x-form.input
                class="col-lg-7"
                label="Short Name"
                name="short_name"
                value="{{ $unit->short_name }}"
            ></x-form.input>
            <x-form.radio
                class="col-lg-7"
                label="Status"
                name="status"
                :options="['Active', 'Inactive']"
                checked="{{ $unit->status }}"
            ></x-form.radio>

            <x-form.button type="submit" label="Submit"></x-form.button>

        </x-form>
    </x-container>
@endsection
