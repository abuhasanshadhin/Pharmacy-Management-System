@extends('layout.app')
@php
    $breadcrumbs = [
        ['text' => 'Inventory'],
        ['text' => 'Purchases']
    ];
@endphp
@section('content')
    <x-container
        title="Purchase Details"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="purchase.index"
        btn_title="Back"
    >
        <div class="">
            <div class="row mb-3">
                <div class="col-lg-6">
                    <table class="">
                        <tr>
                            <th>{{ translator('Invoice Number') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ $purchase->invoice_no }}</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Reference') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ $purchase->reference }}</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Supplier') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ @$purchase->supplier->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Date') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ date('F d, Y', strtotime($purchase->purchase_date)) }}</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Status') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>
                                <span class="badge text-capitalize bg-{{ $purchase->status == 'received'?'success':'danger' }}">{{ $purchase->status }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translator('Created') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ date('F d, Y', strtotime($purchase->created_at)) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6 ">
                    <table class="">
                        <tr>
                            <th>{{ translator('Subtotal') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ formatPrice($purchase->subtotal) }}</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Discount') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ formatPrice($purchase->discount) }}</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Tax') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ formatPrice($purchase->tax) }}</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Total Amount') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ formatPrice($purchase->grand_total) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>{{ translator('Medicine Name') }}</th>
                        <th>{{ translator('Buy Price') }}</th>
                        <th>{{ translator('Quantity') }}</th>
                        <th>{{ translator('Subtotal') }}</th>
                        <th>{{ translator('Discount') }}</th>
                        <th class="text-end">{{ translator('Total') }}</th>
                    </tr>

                    @forelse ($purchase->purchase_details ?? [] as $product)
                        <tr>
                            <td>{{ $product->product->name }}</td>
                            <td>{{ formatPrice($product->purchase_price) }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ formatPrice($product->subtotal) }}</td>
                            <td>{{ formatPrice($product->discount) }}</td>
                            <td class="text-end">{{ formatPrice($product->total) }}</td>
                            @empty
                                <h4>{{ translator('No data available') }}</h4>
                        </tr>
                    @endforelse
                </table>
            </div>
        </div>
    </x-container>
@endsection
