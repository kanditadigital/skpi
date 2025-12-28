<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $title }} &mdash; Stislaravel by Kandita</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/stislaravel/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/stislaravel/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/stislaravel/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/stislaravel/css/all.min.css') }}">
</head>
<body>

    <div id="app">
        <div class="main-wrapper">

            @include('layouts.header')
            @include('layouts.sidebar')

            <div class="main-content">
                <section class="section">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="mt-3">
                                    @include('sweetalert2::index')
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            @include('layouts.footer')

        </div>
    </div>

    <script src="{{ asset('vendor/stislaravel/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('vendor/stislaravel/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/stislaravel/js/scripts.js') }}"></script>
    <script src="{{ asset('vendor/stislaravel/js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('vendor/stislaravel/js/stisla.js') }}"></script>
</body>
</html>
