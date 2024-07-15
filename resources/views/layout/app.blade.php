<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="description"
          content="A comprehensive pharmacy management system that streamlines pharmacy operations, including inventory management, prescription tracking, and customer service.">
    <meta name="keywords"
          content="Pharmacy Management System, Pharmacy Software, Prescription Management, Inventory Management, Healthcare, Pharmacy Operations">
    <meta name="author" content="devsclub.xyz">
    <meta name="robots" content="index, follow">
    <title>{{ config('app.name') }}</title>
    <script>
        window.laravel = {
            baseurl: '{{ url('/')}}'
        }
    </script>
    <link href="{{ @globalAsset(setting('favicon')) }}" rel="icon">
    <link href="{{ @globalAsset(setting('favicon')) }}" rel="apple-touch-icon">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/invoice.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/modify.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icon.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/daterangepicker/daterangepicker.css') }}" />
    @stack('styles')
    <style>
        :root{
            --bs-primary: {{ setting('primary_color') }};
            --bs-btn-bg: {{ setting('primary_color')}};
            --bs-btn-hover-bg: {{ setting('primary_color')}};
            --theme-font-size: 14px,
        }
    </style>
</head>

<body>
@include('layout.partials.header')
@include('layout.partials.sidebar')

<main id="main" class="main">
    @include('layout.partials.error_alert')
    @yield('content')
</main>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
</a>

<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ asset('assets/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/daterangepicker/daterangepicker.min.js') }}"></script>

<script>
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            opens: 'left'
        });
    });
</script>
@stack('scripts')
</body>

</html>
