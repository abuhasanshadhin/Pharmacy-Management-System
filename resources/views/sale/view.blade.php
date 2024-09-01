@extends('layout.app')
@php
    $breadcrumbs = [
        ['text' => 'Inventory'],
        ['text' => 'Sale Details']
    ];
@endphp
@section('content')
    <x-container
        title="Sale Details"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="sale.index"
        btn_title="Back"
    >
        <div class="">
            <div class="row justify-content-end">
                <div class="col-lg-4 text-end">
                    <a class="btn btn-primary btn-sm" href="{{ route('sale.invoice', $sale->id) }}">
                      <i class="bi bi-printer"></i>  {{ translator('Invoice') }}
                    </a>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-6">
                    <table class="">
                        <tr>
                            <th>{{ translator('Invoice Number') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ $sale->invoice_number }}</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Customer') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ @$sale->customer ? $sale->customer->name : 'Walking Customer' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Payment Method') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ @$sale->gateway->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Date') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ date('F d, Y', strtotime($sale->sale_date)) }}</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Status') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>
                                <span class="badge text-capitalize bg-{{ $sale->status == 'sold'?'success':'danger' }}">{{ $sale->status }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translator('Created') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ date('F d, Y', strtotime($sale->created_at)) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6 ">
                    <table class="">
                        <tr>
                            <th>{{ translator('Subtotal') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ formatPrice($sale->subtotal) }}</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Discount') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>-{{ $sale->discount }} ({{ $sale->discount_value_type }})</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Tax') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>-{{ $sale->tax }} ({{ $sale->tax_value_type }})</td>
                        </tr>
                        <tr>
                            <th>{{ translator('Total Amount') }}</th>
                            <th class="mx-2 d-inline-block">:</th>
                            <td>{{ formatPrice($sale->total) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>{{ translator('Medicine Name') }}</th>
                        <th>{{ translator('Sale Price') }}</th>
                        <th>{{ translator('Quantity') }}</th>
                        <th>{{ translator('Subtotal') }}</th>
                        <th>{{ translator('Discount') }}</th>
                        <th class="text-end">{{ translator('Total') }}</th>
                    </tr>

                    @forelse ($sale->sale_details ?? [] as $product)
                        <tr>
                            <td>
                                <img height="50" width="50" src="{{ $product->product->image }}" alt="">
                                {{ $product->product->name }}
                            </td>
                            <td>{{ formatPrice($product->price) }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ formatPrice($product->subtotal) }}</td>
                            <td>{{ formatPrice($product->discount) }}</td>
                            <td class="text-end">{{ formatPrice($product->total) }}</td>
                            @empty
                                <td colspan="6">
                                    <h4 class="text-center my-5">{{ translator('No data available') }}</h4>
                                </td>
                        </tr>
                    @endforelse
                </table>
            </div>
        </div>
    </x-container>
@endsection
