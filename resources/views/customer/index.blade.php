@extends('layout.app')
@php
    $model = 'customer';
    $breadcrumbs = [
            ['text' => 'Customers']
          ];
        $headers = [
            ['text' => 'Name', 'value' => 'name', 'searchable' => true],
            ['text' => 'Email', 'value' => 'email', 'searchable' => true],
            ['text' => 'Phone', 'value' => 'phone', 'searchable' => true],
            ['text' => 'Address', 'value' => 'address'],
            ['text' => 'Due', 'value' => 'due'],
            ['text' => 'Status', 'value' => 'status'],
        ];
        $actions = [
            ['link' => (fn($item) => route('customer.edit', $item->id)), 'icon' => 'bi bi-pencil', 'class' => 'btn-outline-primary',],
            ['link' => (fn($item) => route('customer.destroy', $item->id)), 'icon' => 'bi bi-trash', 'class' => 'btn-outline-danger', 'method' => 'delete',],
        ];
@endphp
@section('content')
    <x-container
        title="Customers"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="customer.create"
        btn_title="Add New"
    >
        <x-table
            :headers="$headers"
            :collection="$customers"
            :actions="[editAction($model), deleteAction($model)]"
        >
        </x-table>
    </x-container>
@endsection
