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

    <title>{{ translator('Login to Your Account') }}</title>
    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/remixicon/remixicon.css') }}" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <style>
        :root {
            --bs-primary: {{ setting('primary_color') }};
            --bs-btn-bg: {{ setting('primary_color')}};
            --bs-btn-hover-bg: {{ setting('primary_color')}};
            --theme-font-size: 14px,
        }
    </style>
</head>
<body class="overflow-hidden">
<main class="">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-6 p-5 offset-1">
                <img class="img-fluid" src="{{ asset('assets/img/login-bg1.png') }}" alt="">
            </div>
            <div class="col-lg-3 offset-1 d-flex flex-column align-items-center justify-content-center">
                <div class="card py-3 px-2 shadow">
                    <div class="card-body">
                        <h5 class="card-title text-center pb-0 fs-4 text-primary ">{{ translator('Login to Your Account') }}</h5>
                        <p class="text-center small text-muted">{{ translator('Enter your username & password to login') }}</p>

                        <form action="{{ route('login') }}" method="POST" class="row g-3">
                            @csrf

                            <div class="col-12">
                                <label for="yourUsername" class="form-label">Username</label>
                                <input type="text" name="login" value="{{ old('login') }}"
                                       class="form-control" id="yourUsername">
                                @error('login')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="yourPassword" class="form-label">{{ translator('Password') }}</label>
                                <input type="password" name="password" class="form-control"
                                       id="yourPassword">
                                @error('password')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" value="true" id="rememberMe"
                                           class="form-check-input">
                                    <label class="form-check-label"
                                           for="rememberMe">{{ translator('Remember me') }}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">{{ translator('Login') }}</button>
                            </div>
                        </form>

                        @if(env('APP_DEMO'))
                            <div class="col-12 mt-2">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form action="{{ route('login') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="login" value="admin">
                                            <input type="hidden" name="password" value="123456">
                                            <button type="submit"
                                                    class="btn btn-success btn-sm w-100">{{ translator('Super Admin') }}</button>
                                        </form>
                                    </div>
                                    <div class="col-lg-6">
                                        <form action="{{ route('login') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="login" value="staff">
                                            <input type="hidden" name="password" value="123456">
                                            <button type="submit"
                                                    class="btn btn-warning btn-sm w-100">{{ translator('Staff') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Vendor JS Files -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<!-- Template Main JS File -->
<script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
