@extends('layout.app')
@php
$breadcrumbs = [
        ['text' => 'Medicines'],
        ['text' => 'Medicine List', 'link' => route('product.index')],
        ['text' => 'Create Medicine']
    ]
@endphp
@section('content')
    <x-container
        title="Medicine"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="product.index"
        btn_title="Back"
    >
        <x-form class="row" action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
            <x-form.input
                type="file"
                class="col-lg-4"
                label="Image"
                name="image"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Barcode"
                name="barcode"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Medicine Name"
                name="name"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Generaic Name"
                name="generaic_name"
            ></x-form.input>
            <x-form.select
                :required="true"
                class="col-lg-4"
                label="Select Category"
                name="category_id">
                @foreach($categories as $cat)
                    <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                @endforeach
            </x-form.select>
            <x-form.select
                :required="true"
                class="col-lg-4"
                label="Select Unit"
                name="unit_id">
                @foreach($units as $unit)
                    <option value="{{ $unit['id'] }}">{{ $unit['name'] }}</option>
                @endforeach
            </x-form.select>
            <x-form.select
                :required="true"
                class="col-lg-4"
                label="Select Menufacturer"
                name="supplier_id">
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier['id'] }}">{{ $supplier['name'] }}</option>
                @endforeach
            </x-form.select>
            <x-form.input
                class="col-lg-4"
                label="Strength"
                name="strength"
            ></x-form.input>
            <x-form.input
                type="number"
                :required="true"
                class="col-lg-4"
                label="Purchase Price"
                name="purchase_price"
            ></x-form.input>
            <x-form.input
                type="number"
                :required="true"
                class="col-lg-4"
                label="Sale Price"
                name="sale_price"
            ></x-form.input>
            <x-form.input
                type="number"
                class="col-lg-2"
                label="Tax"
                name="tax"
                value="0"
            ></x-form.input>
            <x-form.select
                class="col-lg-2"
                label="Value Type"
                name="tax_value_type">
                <option selected value="percent">{{ translator('Percent') }}</option>
                <option value="fixed">{{ translator('Fixed') }}</option>
            </x-form.select>
            <x-form.radio
                class="col-lg-6"
                label="Status"
                name="status"
                :options="['Active', 'Inactive']"
                checked="Active"
            ></x-form.radio>

            <x-form.button type="submit" label="Submit"></x-form.button>

        </x-form>
    </x-container>
@endsection
