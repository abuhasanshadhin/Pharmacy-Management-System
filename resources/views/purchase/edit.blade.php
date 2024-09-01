@extends('layout.inventory_layout')

@section('guest-content')
    <div class="col-lg-12 mb-3">
        <div class="row">
            <div class="col-lg-7">

            </div>
            <div class="col-lg-5 text-end">
                <a href="{{ route('purchase.index') }}" class="btn bg-gradient btn-success">{{ translator('Purchase List') }}</a>
                <a href="{{ url('/') }}" class="btn bg-gradient btn-warning">{{ translator('Dashboard') }}</a>
            </div>
        </div>
    </div>
    <div id="app">
        <purchase-form></purchase-form>
    </div>
@endsection
@push('scripts')
    @vite('resources/js/app.js')
@endpush
