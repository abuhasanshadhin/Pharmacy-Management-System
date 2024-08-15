@extends('layout.app')
@php
    $breadcrumbs = [
        ['text' => 'Supplier', 'link' => route('supplier.index')],
        ['text' => 'Supplier Edit']
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
        <x-form class="row" action="{{ route('supplier.update',$supplier->id) }}" method="put">
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Name"
                name="name"
                value="{{ $supplier->name }}"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Contact Person Name"
                name="contact_person_name"
                value="{{ $supplier->contact_person_name }}"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Email"
                name="email"
                value="{{ $supplier->email }}"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Phone"
                name="phone"
                value="{{ $supplier->phone }}"
            ></x-form.input>
            <x-form.input
                type="number"
                class="col-lg-4"
                label="Payable"
                name="payable"
                value="{{ $supplier->payable ?? 0 }}"
            ></x-form.input>
            <x-form.radio
                class="col-lg-3"
                label="Status"
                name="status"
                :options="['Active', 'Inactive']"
                checked="{{$supplier->status}}"
            ></x-form.radio>
            <x-form.textarea label="Address" value="{{ $supplier->address }}" name="address"></x-form.textarea>

            <x-form.button type="submit" label="Save Changes" variant="primary"></x-form.button>

        </x-form>
    </x-container>
@endsection
