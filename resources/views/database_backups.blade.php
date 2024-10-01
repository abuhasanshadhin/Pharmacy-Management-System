@extends('layout.app')
@php
    $model = 'database_backup';
    $breadcrumbs = [['text' => 'Database Backup']];
        $headers = [
            ['text' => 'File Name','value' => 'file_name', 'searchable' => true],
            ['text' => 'File Size','value' => 'file_size'],
            ['text' => 'Backup By','value' => (fn($item) => @$item->createdBy->name)],
            ['text' => 'Backup Date','value' => (fn($item) => date('Y-m-d',strtotime($item->created_at)))],
        ];
@endphp
@section('content')
    <x-container
        title="Database Backup"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="database_backup.create"
        btn_title="Backup Now"
    >
        <x-table
            :headers="$headers"
            :collection="$collection"
            :actions="[]"
        >
        </x-table>
    </x-container>
@endsection
