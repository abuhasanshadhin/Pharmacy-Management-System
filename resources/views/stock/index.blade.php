@extends('layout.app')
@php
    $breadcrumbs = [
            ['text' => 'Inventory'],
            ['text' => 'Stock']
    ];
        $headers = [
            ['text' => 'Product','value' => (fn($item) => $item->product->name)],
            ['text' => 'Quantity','value' => 'quantity'],
            ['text' => 'Created','value' => (fn($item) => date('Y-m-d',strtotime($item->created_at)))],
            ['text' => 'Updated','value' => (fn($item) => date('Y-m-d',strtotime($item->updated_at)))],
    ];
@endphp
@section('content')
    <x-container
        title="Stock"
        :breadcrumb="$breadcrumbs"
        :button="false"
    >
        <x-table
            :headers="$headers"
            :collection="$collection"
            :actions="[]"
        >
        </x-table>
    </x-container>
@endsection
