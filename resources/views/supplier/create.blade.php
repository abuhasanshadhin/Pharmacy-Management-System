@extends('layout.app')
@php
    $breadcrumbs = [
            ['text' => 'Supplier', 'link' => route('supplier.index')],
        ['text' => 'Create Supplier']
     ];
@endphp
@section('content')
    <x-container
        title="Supplier"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="supplier.index"
        btn_title="Back"
    >
        <x-form class="row" action="{{ route('supplier.store') }}" method="post">
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Name"
                name="name"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Contact Person Name"
                name="contact_person_name"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Email"
                name="email"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Phone"
                name="phone"
            ></x-form.input>
            <x-form.input
                type="number"
                class="col-lg-4"
                label="Payable"
                name="payable"
                value="0"
            ></x-form.input>
            <x-form.radio
                class="col-lg-3"
                label="Status"
                name="status"
                :options="['Active', 'Inactive']"
                checked="Active"
            ></x-form.radio>
            <x-form.textarea label="Address" name="address"></x-form.textarea>

            <x-form.button type="submit" label="Submit"></x-form.button>

        </x-form>
    </x-container>
@endsection
