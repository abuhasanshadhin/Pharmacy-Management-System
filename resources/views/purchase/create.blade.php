@extends('layout.app')
@push('styles')
    <style>
        .purchase-table tr td,
        th {
            padding: 5px 2px !important;
        }

        .purchase-table tr td
        input,
        select {
            width: 73px;
            border: 1px solid #ddd;
            height: 34px;
            border-radius: 7px;
            padding: 0 7px;
            margin: 0 4px;
        }

        .product-search-result {
            position: absolute;
            width: 96%;
            background: #f9f9f9;
            max-height: 300px;
            box-shadow: 0 2px 4px #5555;
            left: 13px;
            top: 40px;
            border-radius: 5px;
            padding: 10px 6px;
            overflow-y: auto;
        }
    </style>
@endpush
@php
    $breadcrumbs = [
        ['text' => 'Inventory'],
        ['text' => 'Purchases', 'link' => route('purchase.index')],
        ['text' => 'New Purchase'],
    ];
@endphp
@section('content')
    <x-container
        title="New Purchase"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="{{ route('purchase.index') }}"
        btn_title="Back"
    >
        <div class="">
            <form action="{{ route('purchase.store') }}" class="row" method="post">
                @csrf
                <livewire:purchase-form></livewire:purchase-form>
            </form>
        </div>
    </x-container>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('body').addClass('toggle-sidebar');
        });
    </script>
@endpush

