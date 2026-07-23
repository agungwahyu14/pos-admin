<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - 5.4.12 Coffee</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        body {
            background-color: var(--bs-primary);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background-color: #fff;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: none;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Logo and Header -->
        <div class="text-center mb-4">
            <img src="{{ asset('images/Blue.png') }}" alt="5.4.12 Coffee Logo" class="mb-3" style="max-height: 80px; object-fit: contain;" onerror="this.style.display='none'">
            <h3 class="fw-bold text-dark mb-1">Welcome Back</h3>
            <p class="text-muted">Sign in to manage 5.4.12 Coffee</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger p-3 mb-4 rounded-3 border-0 bg-danger bg-opacity-10 text-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label fw-medium">Username / Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                    class="form-control bg-white rounded-3 p-2 @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-medium">Password</label>
                <input type="password" name="password" id="password" required
                    class="form-control bg-white rounded-3 p-2">
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input bg-white " id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold">
                Sign In
            </button>
        </form>
    </div>

</body>
</html>
