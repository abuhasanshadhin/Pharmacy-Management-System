@extends('layout.app')
@php
    $breadcrumbs = [
        ['text' => 'Medicines'],
        ['text' => 'Categories', 'link' => route('category.index')],
        ['text' => 'Create Category']
    ];
@endphp
@section('content')
    <x-container
        title="Category"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="category.index"
        btn_title="Back"
    >
        <x-form class="row" :inputsInline="true" :action="route('category.store')" method="post">
            <x-form.select
                class="col-lg-7"
                label="Select Parent"
                name="parent_id"
            >
                @foreach($categories as $cat)
                    <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                @endforeach
            </x-form.select>
            <x-form.input
                :required="true"
                class="col-lg-7"
                label="Name"
                name="name"
            ></x-form.input>
            <x-form.radio
                class="col-lg-7"
                label="Status"
                name="status"
                :options="['Active', 'Inactive']"
                checked="Active"
            ></x-form.radio>

            <x-form.button type="submit" label="Submit"></x-form.button>
        </x-form>
    </x-container>
@endsection
