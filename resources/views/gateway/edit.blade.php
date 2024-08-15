@extends('layout.app')
@php
    $breadcrumbs = [
        ['text' => 'Payment Methods', 'link' => route('gateway.index')],
        ['text' => 'Edit Payment Methods'],
    ];
@endphp
@section('content')
    <x-container
        title="Payment Method"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="gateway.index"
        btn_title="Back"
    >
        <x-form class="row" :action="route('gateway.update', $gateway->id)" method="put">
            <x-form.input
                :required="true"
                class="col-lg-6"
                label="Name"
                name="name"
                value="{{ $gateway->name }}"
            ></x-form.input>
            <x-form.input
                type="number"
                :required="true"
                class="col-lg-4"
                label="Balance"
                name="balance"
                value="{{ $gateway->balance }}"
            ></x-form.input>
            <x-form.radio
                class="col-lg-4"
                label="Status"
                name="status"
                :options="['Active', 'Inactive']"
                checked="{{ $gateway->status }}"
            ></x-form.radio>

            <x-form.button type="submit" label="Submit"></x-form.button>

        </x-form>
    </x-container>
@endsection
