@extends('layout.app')
@php
$model = 'role';
    $breadcrumbs = [
      [ 'text' => 'App Settings'],
      ['text' => 'Role List']
   ];
$headers = [
    ['text' => 'Role Name', 'value' => 'display_name', 'searchable' => true],
    ['text' => 'Guard Name', 'value' => 'guard_name'],
    ['text' => 'Created At', 'value' => (fn($item) => date('Y-m-d',strtotime($item['created_at']))),],
];
@endphp
@section('content')
    <x-container
        title="Roles"
        :breadcrumb="$breadcrumbs"
        :button="true"
        btn_title="Add New"
        url="role.create"
    >
        <x-table
            :headers="$headers"
            :collection="$roles"
            :actions="[editAction($model), deleteAction($model)]"
        >
        </x-table>
    </x-container>
@endsection
