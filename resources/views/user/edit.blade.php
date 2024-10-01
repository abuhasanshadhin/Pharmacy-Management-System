@extends('layout.app')
@php
    $breadcrumbs = [
            ['text' => 'App Settings'],
            ['text' => 'Users','link' => route('user.index')],
            ['text' => 'User Edit']
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
        <x-form class="row" action="{{ route('user.update',$user->id) }}" method="put">
            <x-form.input
                class="col-lg-6"
                label="Name"
                name="name"
                value="{{ $user->name }}"
            ></x-form.input>
            <x-form.input
                class="col-lg-6"
                label="Email"
                name="email"
                type="email"
                value="{{ $user->email }}"
            ></x-form.input>
            <x-form.input
                class="col-lg-6"
                label="Phone"
                name="phone"
                value="{{ $user->phone }}"
            ></x-form.input>
            <x-form.select
                class="col-lg-6"
                label="Select Role"
                name="roles"
            >
                @foreach($roles as $role)
                <option value="{{ $role['name'] }}"
                        @if($user->hasRole($role['name'])) selected @endif>
                    {{ $role['display_name'] }}
                </option>
                @endforeach
            </x-form.select>
            <x-form.input
                class="col-lg-6"
                label="Username"
                name="username"
                value="{{ $user->username }}"
            ></x-form.input>
            <x-form.input
                type="password"
                class="col-lg-6"
                label="Password"
                name="password"
            ></x-form.input>

            <x-form.button type="submit" label="Save Changes"></x-form.button>
        </x-form>
    </x-container>
@endsection
