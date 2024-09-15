@extends('layout.app')
@php
    $model = 'prescription';
    $breadcrumbs = [ ['text' => 'Prescription']];

    $headers = [
        ['text' => 'Prescription No', 'value' => 'no', 'searchable' => true],
        ['text' => 'Patient Name', 'value' => 'patient_name', 'searchable' => true],
        ['text' => 'Patient Phone', 'value' => 'patient_phone', 'searchable' => true],
        ['text' => 'Created at', 'value' => 'created_at'],
    ];

    $actions = [
        ['link' => (fn($item) => route('prescription.edit', $item->id)), 'icon' => 'bi bi-pencil', 'class' => 'btn-outline-primary',],
        ['link' => (fn($item) => route('prescription.destroy', $item->id)), 'icon' => 'bi bi-trash', 'class' => 'btn-outline-danger', 'method' => 'delete',],
    ];
@endphp
@section('content')
    <x-container
        title="Prescription"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="prescription.create"
        btn_title="Add New"
    >
        <x-table
            :headers="$headers"
            :collection="$prescription"
            :actions="[editAction($model), showAction($model), deleteAction($model)]"
        >
        </x-table>
    </x-container>
@endsection
