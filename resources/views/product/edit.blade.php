@extends('layout.app')
@php
    $breadcrumbs = [
            ['text' => 'Medicines'],
            ['text' => 'Medicine List', 'link' => route('product.index')],
            ['text' => 'Edit Medicine']
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
        <x-form class="row" action="{{ route('product.update',$product->id) }}" method="put" enctype="multipart/form-data">
            <div class="col-lg-1 pt-4">
                <img height="40" width="40" src="{{ $product->image }}" alt="">
            </div>
            <x-form.input
                type="file"
                class="col-lg-3"
                label="Image"
                name="image"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Barcode"
                name="barcode"
                value="{{ $product->barcode }}"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Medicine Name"
                name="name"
                value="{{ $product->name }}"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Generaic Name"
                name="generaic_name"
                value="{{ $product->generaic_name }}"
            ></x-form.input>
            <x-form.select
                :required="true"
                class="col-lg-4"
                label="Select Category"
                name="category_id">
                @foreach($categories as $cat)
                    <option value="{{ $cat['id'] }}" @if($cat['id'] == $product->category_id) selected @endif>{{ $cat['name'] }}</option>
                @endforeach
            </x-form.select>
            <x-form.select
                :required="true"
                class="col-lg-4"
                label="Select Unit"
                name="unit_id">
                @foreach($units as $unit)
                    <option value="{{ $unit['id'] }}" @if($unit['id'] == $product->unit_id) selected @endif>
                        {{ $unit['name'] }}
                    </option>
                @endforeach
            </x-form.select>
            <x-form.select
                :required="true"
                class="col-lg-4"
                label="Select Menufacturer"
                name="supplier_id">
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier['id'] }}" @if($supplier['id'] == $product->supplier_id) selected @endif>
                        {{ $supplier['name'] }}
                    </option>
                @endforeach
            </x-form.select>
            <x-form.input
                class="col-lg-4"
                label="Strength"
                name="strength"
                value="{{ $product->strength }}"
            ></x-form.input>
            <x-form.input
                type="number"
                :required="true"
                class="col-lg-4"
                label="Purchase Price"
                name="purchase_price"
                value="{{ $product->purchase_price }}"
            ></x-form.input>
            <x-form.input
                type="number"
                :required="true"
                class="col-lg-4"
                label="Sale Price"
                name="sale_price"
                value="{{ $product->sale_price }}"
            ></x-form.input>
            <x-form.input
                type="number"
                class="col-lg-2"
                label="Tax"
                name="tax"
                value="0"
                value="{{ $product->tax }}"
            ></x-form.input>
            <x-form.select
                class="col-lg-2"
                label="Value Type"
                name="tax_value_type">
                <option @if($product->tax_value_type == 'percent') selected @endif value="percent">
                    Percent</option>
                <option @if($product->tax_value_type == 'fixed') selected @endif value="fixed">Fixed</option>
            </x-form.select>
            <x-form.radio
                class="col-lg-6"
                label="Status"
                name="status"
                :options="['Active', 'Inactive']"
                checked="{{ $product->status }}"
            ></x-form.radio>

            <x-form.button type="submit" label="Submit" variant="success"></x-form.button>

        </x-form>
    </x-container>
@endsection
