@extends('layout.app')
@php
    $model = 'category';
    $breadcrumbs = [
        ['text' => 'Medicines'],
        ['text' => 'Categories']
    ];
    $headers = [
        ['text' => 'Parent', 'value' => (fn($item) => !empty($item->parent) ? $item->parent->name : '-')],
        ['text' => 'Name', 'value' => 'name', 'searchable' => true],
        ['text' => 'Status', 'value' => 'status'],
    ];
@endphp
@section('content')
    <x-container
        title="Category"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="category.create"
        btn_title="Add New"
    >
        <x-table
            :headers="$headers"
            :collection="$categories"
            :actions="[editAction($model),deleteAction($model)]"
        >
        </x-table>
    </x-container>
@endsection
