@extends('layout.app')
@php
    $model = 'sale';
    $breadcrumbs = [['text' => 'Inventory'], ['text' => 'Sale']];
    $headers = [
        ['text' => 'Invoice No','value' => 'invoice_number', 'searchable' => true],
        ['text' => 'Subtotal','value' => 'subtotal'],
        ['text' => 'Tax','value' => 'tax'],
        ['text' => 'Discount','value' => 'discount'],
        ['text' => 'Total','value' => 'total'],
        ['text' => 'Status','value' => 'status'],
        ['text' => 'Date','value' => (fn($item) => date('Y-m-d',strtotime($item->created_at)))],
    ];
@endphp
@section('content')
    <x-container
        title="Sale"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="sale.create"
        btn_title="Add New"
    >
        <x-table
            :headers="$headers"
            :collection="$collection"
            :actions="[showAction($model), deleteAction($model), extraAction('sale.invoice','id','btn-outline-primary','bi bi-printer')]"
        >
        </x-table>
    </x-container>
@endsection
