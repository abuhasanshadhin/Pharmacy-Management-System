@extends('layout.app')
@php
$model = 'user';
$breadcrumbs = [
    ['text' => 'App Settings'],
    ['text' => 'User List']
];
    $headers = [
        ['text' => 'Name', 'value' => 'name', 'searchable' => true],
        ['text' => 'Role', 'value' => (fn($item) => $item->roles ? $item->getRoleNames() :'-')],
        ['text' => 'Username', 'value' => 'username'],
        ['text' => 'Email', 'value' => 'email'],
        ['text' => 'Phone', 'value' => 'phone'],
        ['text' => 'Status', 'value' => 'status'],
    ];

@endphp
@section('content')
    <x-container
        title="Users"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="user.create"
        btn_title="Add New"
    >
        <x-table
            :headers="$headers"
            :collection="$users"
            :actions="[editAction($model), deleteAction($model)]"
        >
        </x-table>
    </x-container>
@endsection
