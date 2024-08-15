@extends('layout.app')
@php
$model = 'gateway';
$breadcrumbs = [
    ['text' => 'Payment Methods']
];
    $headers = [
        ['text' => 'Name', 'value' => 'name', 'searchable' => true],
        ['text' => 'Balance', 'value' => 'balance'],
        ['text' => 'Status', 'value' => 'status'],
    ];
@endphp
@section('content')
    <x-container
        title="Payment Methods"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="gateway.create"
        btn_title="Add New"
    >
        <x-table
            :headers="$headers"
            :collection="$gateway"
            :actions="[editAction($model), deleteAction($model)]"
        >
        </x-table>
    </x-container>
@endsection
