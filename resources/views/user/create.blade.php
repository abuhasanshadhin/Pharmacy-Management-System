@extends('layout.app')
@php
    $breadcrumbs = [
            ['text' => 'App Settings'],
            ['text' => 'Users','link' => route('user.index')],
            ['text' => 'Create User']
        ];
@endphp
@section('content')
    <x-container
        title="Users"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="user.index"
        btn_title="Back"
    >
        <x-form class="row" action="{{ route('user.store') }}" method="post">
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Name"
                name="name"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Email"
                name="email"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Phone"
                name="phone"
            ></x-form.input>
            <x-form.select
                :required="true"
                class="col-lg-4"
                label="Select Role"
                name="roles[]"
            >
                @foreach($roles as $role)
                    <option value="{{ $role['name'] }}">{{ $role['display_name'] }}</option>
                @endforeach
            </x-form.select>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Username"
                name="username"
            ></x-form.input>
            <x-form.input
                :required="true"
                class="col-lg-4"
                label="Password"
                name="password"
            ></x-form.input>

            <x-form.button type="submit" label="Submit"></x-form.button>
        </x-form>
    </x-container>
@endsection
