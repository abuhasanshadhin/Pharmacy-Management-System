@extends('layout.app')
@php
    $breadcrumbs = [
            ['text' => 'Customers', 'link' => route('customer.index')],
            ['text' => 'Customer Edit']
     ];
@endphp
@section('content')
    <x-container
        title="Customer"
        :breadcrumb="$breadcrumbs"
        :button="true"
        :url="route('customer.index')"
        btn_title="Back"
    >
        <x-form class="row" :action="route('customer.update',$customer->id)" method="put">
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Name"
                name="name"
                value="{{ $customer->name }}"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Email"
                name="email"
                value="{{ $customer->email }}"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Phone"
                name="phone"
                value="{{ $customer->phone }}"
            ></x-form.input>
            <x-form.input
                type="number"
                class="col-lg-4"
                label="Due"
                name="due"
                value="{{ $customer->due ?? 0 }}"
            ></x-form.input>
            <x-form.radio
                class="col-lg-3"
                label="Status"
                name="status"
                :options="['Active', 'Inactive']"
                checked="{{$customer->status}}"
            ></x-form.radio>
            <x-form.textarea label="Address" value="{{ $customer->address }}" name="address"></x-form.textarea>

            <x-form.button type="submit" label="Save Changes" variant="primary"></x-form.button>

        </x-form>
    </x-container>
@endsection
