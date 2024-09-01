@extends('layout.app')
@php
$model = 'purchase';
$breadcrumbs = [
        ['text' => 'Inventory'],
        ['text' => 'Purchases']
];
    $headers = [
        ['text' => 'Reference','value' => 'reference', 'searchable' => true],
        ['text' => 'Supplier','value' => (fn($item) => $item['supplier']?$item['supplier']['name']:'-')],
        ['text' => 'Date','value' => 'purchase_date', 'searchable' => true],
        ['text' => 'Subtotal','value' => 'subtotal'],
        ['text' => 'Tax','value' => 'tax'],
        ['text' => 'Discount','value' => 'discount'],
        ['text' => 'Total','value' => 'grand_total'],
        ['text' => 'Status','value' => 'status'],
];
@endphp
@section('content')
    <x-container
        title="Purchases"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="purchase.create"
        btn_title="Add New"
    >
        <x-table
            :headers="$headers"
            :collection="$collection"
            :actions="[showAction($model), deleteAction($model), extraAction('purchase.invoice','id','btn-outline-primary','bi bi-printer')]"
        >
        </x-table>
    </x-container>
@endsection
