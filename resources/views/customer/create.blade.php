@extends('layout.app')
@php
    $breadcrumbs = [
            ['text' => 'Customers', 'link' => route('customer.index')],
            ['text' => 'Create Customer']
     ];
@endphp
@section('content')
    <x-container
        title="Customer"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="customer.index"
        btn_title="Back"
    >
        <x-form class="row" :action="route('customer.store')" method="post">
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Name"
                name="name"
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
                label="Due"
                name="due"
                value="0"
            ></x-form.input>
            <x-form.radio
                class="col-lg-4"
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
