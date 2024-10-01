@extends('layout.app')
@push('styles')
    <style>
        .brand-block .card-body {
            height: 120px;
            width: 120px;
            margin: auto;
            background: #f6f6f68f;
        }
        .brand-block .card-body img{
            height: 100%;
            width: 100%;
            object-fit: contain;
        }
    </style>
@endpush
@php
    $breadcrumbs = [
        ['text' => 'App Settings'],
        ['text' => 'Settings']
];
@endphp
@section('content')
    <x-container title="Settings" :breadcrumb="$breadcrumbs">
        <x-form class="row" :action="route('update.settings')" method="post">
            <div class="col-lg-7">
                <div class="row">
                    <x-form.input
                        name="app_name"
                        label="Application Name"
                        class="col-lg-6"
                        value="{{ setting('app_name') }}"
                    ></x-form.input>
                    <x-form.input
                        name="title"
                        label="Application Title"
                        class="col-lg-6"
                        value="{{ setting('title') }}"
                    ></x-form.input>
                    <x-form.input
                        name="currency"
                        label="Currency"
                        class="col-lg-6"
                        value="{{ setting('currency') }}"
                    ></x-form.input>
                    <x-form.input
                        name="currency_symbol"
                        label="Currency Symbol"
                        class="col-lg-6"
                        value="{{ setting('currency_symbol') }}"
                    ></x-form.input>
                    <x-form.input
                        name="phone"
                        label="Phone"
                        class="col-lg-6"
                        value="{{ setting('phone') }}"
                    ></x-form.input>
                    <x-form.input
                        name="email"
                        label="Email"
                        class="col-lg-6"
                        value="{{ setting('email') }}"
                    ></x-form.input>
                    <x-form.textarea
                        name="address"
                        label="Address"
                        class="col-lg-12"
                        value="{{ setting('address') }}"
                    ></x-form.textarea>
                    <x-form.input
                        name="footer_text"
                        label="Footer Text"
                        class="col-lg-6"
                        value="{{ setting('footer_text') }}"
                    ></x-form.input>
                    <x-form.input
                        type="color"
                        name="primary_color"
                        label="Primary Color"
                        class="col-lg-6"
                        value="{{ setting('primary_color') }}"
                    ></x-form.input>

                </div>
            </div>
            <div class="col-lg-5">
                <x-form.radio
                    name="logo_visible"
                    label="Set logo as"
                    labelColumn="col-lg-12"
                    inputColumn="col-lg-8"
                    class="col-lg-12"
                    :options="['Application Name','Logo Image']"
                    checked="{{ setting('logo_visible') }}"
                ></x-form.radio>
                <div class="row">
                    <div class="col-lg-6">

                        <div class="brand-block card">
                            <div class="card-header border-0">
                                <h6 class="mb-0 text-dark fw-bold">{{ translator('Favicon') }}</h6>
                            </div>
                            <div class="card-body p-3 text-center">
                                @php $favicon = !empty(setting('favicon')) ? asset('storage/'.setting('favicon')) : asset('images/no-image.png')  @endphp
                                <img src="{{ $favicon }}" alt="">
                                <input type="file" name="favicon" class="d-none" id="favicon">
                            </div>
                            <div class="card-footer border-0">
                                <label for="favicon" class="btn btn-sm btn-white shadow-sm text-primary  w-100">
                                    <i class="bi bi-upload"></i> {{ translator('Choose file here') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="brand-block card">
                            <div class="card-header border-0">
                                <h6 class="mb-0 text-dark fw-bold">{{ translator('Logo') }}</h6>
                            </div>
                            <div class="card-body p-3 text-center">
                                @php $logo = !empty(setting('logo')) ? asset('storage/'.setting('logo')) : asset('images/no-image.png')  @endphp
                                <img src="{{ $logo  }}" alt="">
                                <input type="file" name="logo" class="d-none" id="logo">
                            </div>
                            <div class="card-footer border-0">
                                <label for="logo" class="btn btn-sm btn-white shadow-sm text-primary  w-100">
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
