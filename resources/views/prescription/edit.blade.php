@extends('layout.app')
@php
    $breadcrumbs = [
            ['text' => 'Prescriptions', 'link' => route('prescription.index')],
            ['text' => 'Prescription Edit']
     ];
@endphp
@section('content')
    <x-container
        title="Prescription"
        :breadcrumb="$breadcrumbs"
        :button="true"
        :url="route('prescription.index')"
        btn_title="Back"
    >
        <x-form class="row" :action="route('prescription.update',$prescription->id)" method="put">
            <x-form.input
                class="col-lg-4"
                label="Patient Name"
                name="patient_name"
                value="{{ $prescription->patient_name }}"
            ></x-form.input>
            <x-form.input
                class="col-lg-4"
                label="Patient Phone"
                name="patient_phone"
                value="{{ $prescription->patient_phone }}"
            ></x-form.input>
            <x-form.input
                type="number"
                class="col-lg-4"
                label="Patient Age"
                name="patient_age"
                value="{{ $prescription->patient_age }}"
            ></x-form.input>

            <div class="mb-3 col-lg-4">
                <label for="664cec7fd20e8" class="form-label mb-0 ">Prescription</label>
                <input id="664cec7fd20e8" type="file" name="prescription_file" class="form-control ">
            </div>

            <x-form.textarea label="Address" value="{{ $prescription->patient_address }}" name="address"></x-form.textarea>

            <x-form.button type="submit" label="Save Changes" variant="primary"></x-form.button>
        </x-form>
    </x-container>
@endsection
