<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $title ?? 'Login' }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('vendor/stislaravel/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/stislaravel/css/all.min.css') }}">

    <style>
        body {
            background-color: #f4f6f9;
        }
        .login-wrapper {
            min-height: 100vh;
        }
        .login-card {
            max-width: 420px;
            width: 100%;
            border-radius: 12px;
        }
        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }
        .divider::before,
        .divider::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #ddd;
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }
        .divider span {
            background: #fff;
            padding: 0 10px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container login-wrapper d-flex align-items-center justify-content-center">
    <div class="card shadow-sm login-card">
        <div class="card-body p-4">

            <div class="text-center mb-4">
                <h4 class="font-weight-bold">Login</h4>
                <p class="text-muted mb-0">Silakan masuk untuk melanjutkan</p>
            </div>

            {{-- Error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.authenticate') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" required autofocus>
                </div>

                {{-- Password --}}
                <div class="form-group mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                {{-- Remember --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" class="custom-control-input" id="remember">
                        <label class="custom-control-label" for="remember">Ingat saya</label>
                    </div>
                    <a href="">Lupa password?</a>
                </div>

                <x-button type="submit" variant="primary" block>
                    Login
                </x-button>
            </form>

            {{-- Divider --}}
            <div class="divider">
                <span>ATAU</span>
            </div>

            {{-- Google Login --}}
            <x-button variant="danger" block href="{{ route('google.login') }}">
                <i class="fab fa-google mr-2"></i> Login dengan Google
            </x-button>

        </div>
    </div>
</div>

<script src="{{ asset('vendor/stislaravel/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('vendor/stislaravel/js/bootstrap.min.js') }}"></script>
</body>
</html>
