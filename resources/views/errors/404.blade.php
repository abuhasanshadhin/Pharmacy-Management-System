@extends('layout.guest_layout')
@section('guest-content')
    <main>
        <div class="container">
            <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
                <img src="{{ asset('images/404.svg') }}" height="400" class="mb-3" alt="Page Not Found">
                <div class="credits">
                    <h2>{{ translator('The page you are looking for doesn\'t exist') }}.</h2>
                    <a href="/" class=""><i class="bi bi-arrow-left"></i>{{ translator('Back to home') }}</a>
                </div>
            </section>

        </div>
    </main>
@endsection
