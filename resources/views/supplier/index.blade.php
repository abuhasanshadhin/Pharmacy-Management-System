@extends('layout.app')
@php
    $model = 'supplier';
    $breadcrumbs = [
            ['text' => 'Suppliers']
        ];
        $headers = [
            ['text' => 'Name', 'value' => 'name','searchable' => true],
            ['text' => 'Contact Person', 'value' => 'contact_person_name','searchable' => true],
            ['text' => 'Email', 'value' => 'email','searchable' => true],
            ['text' => 'Phone', 'value' => 'phone','searchable' => true],
            ['text' => 'Address', 'value' => 'address'],
            ['text' => 'Payable', 'value' => 'payable'],
            ['text' => 'Status', 'value' => 'status','searchable' => true],
        ];
@endphp
@section('content')
    <x-container
        title="Suppliers"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="supplier.create"
        btn_title="Add New"
    >
        <x-table
            :headers="$headers"
            :collection="$suppliers"
            :actions="[editAction($model), deleteAction($model)]"
        >
        </x-table>
    </x-container>
@endsection
