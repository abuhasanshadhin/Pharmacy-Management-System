@extends('layout.app')
@php
    $breadcrumbs = [
            ['text' => 'Inventory'],
            ['text' => 'Purchases']
    ];
@endphp
@section('content')
    <x-container
        title="Purchase Invoice"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="purchase.index"
        btn_title="Back"
    >
        <div class="">
            <div class="row text-end">
                <div class="col-lg-12">
                    <a href="javascript:" id="window-printer" onclick="return(window.print())" class="text-primary"><i class="bi bi-printer"></i> {{translator('Print')}}</a>
                </div>
            </div>
            <div class="invoice" id="purchaseInvoice">
                <table class="table header">
                    <tr>
                        <td>
                            <div class="company-brand">
                                <img class="logo" src="{{ globalAsset(setting('favicon')) }}" alt="">
                            </div>
                            <p><b>{{ translator('Invoice Number') }}:</b> {{ $purchase->invoice_no }}</p>
                            <p><b>{{ translator('Date') }}:</b> {{ date('F d, Y', strtotime($purchase->purchase_date)) }}
                            </p>
                        </td>
                        <td class="text-end">
                            <h1 class="invoice-title">{{ translator('Purchase Invoice') }}</h1>
                            <p><b>{{ translator('Reference') }}</b> : {{ $purchase->reference }}</p>
                        </td>

                    </tr>
                </table>
                <table class="table table-borderless border-bottom">
                    <tr>
                        <td>
                            <h3>{{ translator('Bill From') }}</h3>
                            <p><b>{{ @$purchase->supplier->name }}</b></p>
                            <p>{{ @$purchase->supplier->address }}</p>
                            <p>{{ translator('Phone Number') }}: {{ @$purchase->supplier->phone }}</p>
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
                        <th>{{ translator('Sale Price') }}</th>
                        <th>{{ translator('Quantity') }}</th>
                        <th>{{ translator('Subtotal') }}</th>
                        <th>{{ translator('Discount') }}</th>
                        <th class="text-end">{{ translator('Total') }}</th>
                    </tr>

                    @forelse ($purchase->purchase_details ?? [] as $product)
                        <tr>
                            <td>{{ @$product->product->name }}</td>
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
                <table class="table">
                    <tr>
                        <td>
                            <p><b>{{ translator('Notes') }}</b>: {{ $purchase->note }}</p>
                            <p class="text-capitalize"><b>{{ translator('Status') }}</b>: {{ $purchase->status }}</p>
                        </td>
                        <td class="text-end">
                            <table class="table table-borderless estimate-table">
                                <tr>
                                    <td>{{ translator('Subtotal') }}:</td>
                                    <td>{{ formatPrice($purchase->subtotal) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ translator('Discount') }}:</td>
                                    <td>{{ formatPrice($purchase->discount) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ translator('Tax') }}:</td>
                                    <td>{{ formatPrice($purchase->tax) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ translator('Total Amount') }}:</td>
                                    <td>{{ formatPrice($purchase->grand_total) }}</td>
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
