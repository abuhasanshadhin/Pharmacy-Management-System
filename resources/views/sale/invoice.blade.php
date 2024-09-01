@extends('layout.app')
@php
    $breadcrumbs = [
            ['text' => 'Inventory'],
            ['text' => 'Sale']
    ];
@endphp
@section('content')
    <x-container
        title="Sale Invoice"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="sale.index"
        btn_title="Back"
    >
        <div class="">
            <div class="row text-end">
                <div class="col-lg-12">
                    <a href="javascript:" id="window-printer" onclick="return(window.print())" class="text-primary"><i
                            class="bi bi-printer"></i> {{translator('Print')}}</a>
                </div>
            </div>
            <div class="invoice" id="purchaseInvoice">
                <table class="table header">
                    <tr>
                        <td>
                            <div class="company-brand">
                                <img class="logo" src="{{ globalAsset(setting('favicon')) }}" alt="">
                            </div>
                            <p><b>{{ translator('Invoice Number') }}:</b> {{ $sale->invoice_number }}</p>

                        </td>
                        <td class="text-end">
                            <h1 class="invoice-title">{{ translator('Sale Invoice') }}</h1>
                            <p><b>{{ translator('Date') }}:</b> {{ date('F d, Y', strtotime($sale->purchase_date)) }}
                            </p>
                        </td>

                    </tr>
                </table>
                <table class="table table-borderless border-bottom">
                    <tr>
                        <td>
                            <h3>{{ translator('Bill From') }}</h3>
                            @if($sale->customer)
                                <p><b>{{ @$sale->customer->name }}</b></p>
                                <p>{{ @$sale->customer->address }}</p>
                                <p>{{ translator('Phone Number') }}: {{ @$sale->customer->phone }}</p>
                            @else
                                <p>{{ translator('Walking Customer') }}</p>
                            @endif
                        </td>
                        <td class="text-end">
                            <h3>{{ translator('Bill To') }}</h3>
                            <p><b>{{ setting('app_name') }}</b></p>
                            <p>{{ setting('address') }}</p>
                            <p>{{ translator('Phone Number') }}: {{ setting('phone') }}</p>
                        </td>
                    </tr>
                </table>
                <table class="table">
                    <tr>
                        <th>{{ translator('Medicine Name') }}</th>
                        <th>{{ translator('Buy Price') }}</th>
                        <th>{{ translator('Quantity') }}</th>
                        <th>{{ translator('Subtotal') }}</th>
                        <th>{{ translator('Discount') }}</th>
                        <th class="text-end">{{ translator('Total') }}</th>
                    </tr>

                    @forelse ($sale->sale_details ?? [] as $product)
                        <tr>
                            <td>{{ @$product->product->name }}</td>
                            <td>{{ formatPrice($product->price) }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ formatPrice($product->subtotal) }}</td>
                            <td>{{ formatPrice($product->discount) }}</td>
                            <td class="text-end">{{ formatPrice($product->total) }}</td>
                            @empty
                                <td colspan="6">
                                    <h4>{{ translator('No data available') }}</h4>
                                </td>
                        </tr>
                    @endforelse
                </table>
                <table class="table">
                    <tr>
                        <td>
                            <p class="text-capitalize"><b>{{ translator('Status') }}</b>: <span
                                    class="badge bg-{{ $sale->status == 'sold' ?'success':'danger' }}">{{ $sale->status }}</span>
                            </p>
                        </td>
                        <td class="text-end">
                            <table class="table table-borderless estimate-table">
                                <tr>
                                    <td>{{ translator('Subtotal') }}:</td>
                                    <td>{{ formatPrice($sale->subtotal) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ translator('Discount') }}:</td>
                                    <td>{{ formatPrice($sale->discount) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ translator('Tax') }}:</td>
                                    <td>{{ formatPrice($sale->tax) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ translator('Total Amount') }}:</td>
                                    <td>{{ formatPrice($sale->total) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table class="table table-borderless mt-5">
                    <tr>
                        <td class="text-center">
                            <p class="text-muted">{{ config('app.url') }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </x-container>
@endsection
