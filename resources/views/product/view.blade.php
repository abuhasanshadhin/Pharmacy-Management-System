@extends('layout.app')
@php
    $breadcrumbs = [
            ['text' => 'Medicines'],
            ['text' => 'Medicine List', 'link' => route('product.index')],
            ['text' => 'Medicines View']
        ]
@endphp
@section('content')
    <x-container
        title="Medicines"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="product.index"
        btn_title="Back"
    >
        <div class="row">
            <div class="col-md-4">
                <img src="{{ $product->image }}" alt="Product Image" class="img-fluid">
                <h2 class="text-center text-dark">{{ $product->name }}</h2>
            </div>
            <div class="col-md-8">
                <dl class="row">
                    <dt class="col-sm-3">{{ translator('Barcode') }}:</dt>
                    <dd class="col-sm-9">{{ $product->barcode }}</dd>

                    <dt class="col-sm-3">{{ translator('SKU') }}:</dt>
                    <dd class="col-sm-9">{{ $product->sku }}</dd>

                    <dt class="col-sm-3">{{ translator('Medicine Name') }}:</dt>
                    <dd class="col-sm-9">{{ $product->name }}</dd>

                    <dt class="col-sm-3">{{ translator('Generic Name') }}:</dt>
                    <dd class="col-sm-9">{{ $product->generic_name }}</dd>

                    <dt class="col-sm-3">{{ translator('Supplier') }}:</dt>
                    <dd class="col-sm-9">{{ $product->supplier->name }}</dd>

                    <dt class="col-sm-3">{{ translator('Category') }}:</dt>
                    <dd class="col-sm-9">{{ $product->category->name }}</dd>

                    <dt class="col-sm-3">{{ translator('Unit') }}:</dt>
                    <dd class="col-sm-9">{{ $product->unit->name }}</dd>

                    <dt class="col-sm-3">{{ translator('Purchase Price') }}:</dt>
                    <dd class="col-sm-9">{{ $product->purchase_price }}</dd>

                    <dt class="col-sm-3">{{ translator('Sale Price') }}:</dt>
                    <dd class="col-sm-9">{{ $product->sale_price }}</dd>

                    <dt class="col-sm-3">{{ translator('Tax') }}:</dt>
                    <dd class="col-sm-9">
                        {{ $product->tax }}
                        <span class="text-capitalize">({{ $product->tax_value_type }})</span>
                    </dd>

                    <dt class="col-sm-3">{{ translator('Status') }}:</dt>
                    <dd class="col-sm-9">
                        <span class="badge text-capitalize {{ $product->status =='active'?'bg-success':'bg-danger' }}" >
                            {{ $product->status }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>
    </x-container>
@endsection
