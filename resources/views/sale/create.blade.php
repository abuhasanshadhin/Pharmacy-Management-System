@extends('layout.guest_layout')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
@endpush
@section('guest-content')
    <div class="container-fluid pos-container">
        <livewire:p-o-s></livewire:p-o-s>
    </div>
@endsection

