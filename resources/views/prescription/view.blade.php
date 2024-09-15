@extends('layout.app')
@php
    $breadcrumbs = [
            ['text' => 'Prescriptions', 'link' => route('prescription.index')],
            ['text' => 'Prescription View']
     ];
@endphp
@section('content')
    <x-container
        title="Prescription"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="prescription.index"
        btn_title="Back"
    >
        <div class="row">
            <div class="col-md-5">
                <h4>{{ translator('Patient') }}</h4>
                <hr>
                <dl class="row">
                    <dt class="col-sm-4">{{ translator('Patient Name') }}:</dt>
                    <dd class="col-sm-8">{{ $prescription->patient_name }}</dd>
                </dl>
                <dl class="row">
                    <dt class="col-sm-4">{{ translator('Patient Phone') }}:</dt>
                    <dd class="col-sm-8">{{ $prescription->patient_phone }}</dd>
                </dl>
                <dl class="row">
                    <dt class="col-sm-4">{{ translator('Patient Age') }}:</dt>
                    <dd class="col-sm-8">{{ $prescription->patient_age }}</dd>
                </dl>
                <dl class="row">
                    <dt class="col-sm-4">{{ translator('Patient Address') }}:</dt>
                    <dd class="col-sm-8">{{ $prescription->patient_address }}</dd>
                </dl>
            </div>
            <div class="col-lg-7">
                <h4>{{ translator('Prescription') }}</h4>
                <hr>
                <img  src="{{ globalAsset($prescription->prescription_file) }}" alt="Product Image" class="img-fluid">
            </div>
        </div>
    </x-container>
@endsection
