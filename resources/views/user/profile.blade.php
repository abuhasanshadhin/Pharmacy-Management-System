@extends('layout.app')
@push('styles')
    <style>
        .brand-block .card-body {
            height: 120px;
            width: 120px;
            margin: auto;
            background: #f6f6f68f;
        }

        .brand-block .card-body img {
            height: 100%;
            width: 100%;
            object-fit: contain;
        }
    </style>
@endpush
@php
    $breadcrumbs = [
        ['text' => 'App Settings'],
        ['text' => 'Profile']
];
@endphp
@section('content')
    <x-container title="Settings" :breadcrumb="$breadcrumbs">
        <x-form class="row" :action="route('user.update-profile')" method="post">
            <div class="col-lg-7">
                <div class="row">
                    <x-form.input
                        name="username"
                        label="Username"
                        class="col-lg-6"
                        value="{{ $user->username }}"
                    ></x-form.input>
                    <x-form.input
                        name="name"
                        label="Name"
                        class="col-lg-6"
                        value="{{ $user->name }}"
                    ></x-form.input>
                    <x-form.input
                        name="email"
                        label="Email"
                        class="col-lg-6"
                        value="{{ $user->email }}"
                    ></x-form.input>
                    <x-form.input
                        name="phone"
                        label="Phone"
                        class="col-lg-6"
                        value="{{ $user->phone }}"
                    ></x-form.input>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="brand-block card">
                            <div class="card-header border-0">
                                <h6 class="mb-0 text-dark fw-bold">{{ translator('Profile Image') }}</h6>
                            </div>
                            <div class="card-body p-3 text-center">
                                @if(!empty($user->profile_image))
                                    <img src="{{ globalAsset($user->profile_image) }}" alt="profile-image">
                                @else
                                    <img src="{{ asset($user->profile_image) }}" alt="profile-image">
                                @endif
                                <input type="file" accept="image/*" name="profile_image" class="d-none"
                                       id="profile-image">
                            </div>
                            <div class="card-footer border-0">
                                <label for="profile-image" class="btn btn-sm btn-light shadow-sm text-primary  w-100">
                                    <i class="bi bi-upload"></i> {{ translator('Choose file here') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-form.button type="submit" label="Save Changes" variant="primary"></x-form.button>
        </x-form>
    </x-container>
@endsection
