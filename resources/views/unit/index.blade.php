@extends('layout.app')
@php
$model= 'unit';
$breadcrumbs = [
        ['text' => 'Medicines'],
        ['text' => 'Units']
    ];
    $headers = [
        ['text' => 'Name', 'value' => 'name', 'searchable' => true],
        ['text' => 'Short Name', 'value' => (fn($item) => !empty($item->short_name) ? $item->short_name : '-')],
        ['text' => 'Status', 'value' => 'status'],
    ];
@endphp
@section('content')
    <x-container
        title="Unit"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="unit.create"
        btn_title="Add New"
    >
        <x-table
            :headers="$headers"
            :collection="$units"
            :actions="[editAction($model),deleteAction($model)]"
        ></x-table>
    </x-container>
@endsection
