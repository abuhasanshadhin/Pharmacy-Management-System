@extends('layout.app')
@php
    $breadcrumbs = [
        ['text' => 'App Settings'],
        ['text' => 'Change Password']
];
@endphp
@section('content')
    <x-container title="Settings" :breadcrumb="$breadcrumbs">
        <x-form :action="route('user.change-password')" method="post">
            <x-form.input
                type="password"
                :reduired="true"
                name="current_password"
                label="Current Password"
                class="col-lg-6"
                value="{{ @old('current_password') }}"
            ></x-form.input>
            <x-form.input
                type="password"
                :reduired="true"
                name="password"
                label="New Password"
                class="col-lg-6"
            ></x-form.input>
            <x-form.input
                type="password"
                :reduired="true"
                name="password_confirmation"
                label="Confirmed Password"
                class="col-lg-6"
            ></x-form.input>

            <x-form.button type="submit" label="Save Changes" variant="primary"></x-form.button>
        </x-form>
    </x-container>
@endsection
