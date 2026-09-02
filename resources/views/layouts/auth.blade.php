<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Authentication' }} - {{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; min-height: 100vh; }
        .auth-container { max-width: 420px; margin: 0 auto; padding: 2rem 1rem; min-height: 100vh; display: flex; flex-direction: column; justify-content: center; }
        .auth-card { border: none; border-radius: 0.75rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08); }
        .auth-header { text-align: center; margin-bottom: 1.5rem; }
        .auth-header h1 { font-size: 1.5rem; font-weight: 600; }
        .auth-header p { color: #6c757d; font-size: 0.875rem; }
        .auth-logo { font-size: 2rem; margin-bottom: 0.5rem; }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <div class="auth-logo"><i class="bi bi-envelope-paper-heart text-primary"></i></div>
            <h1>{{ $title ?? 'Welcome' }}</h1>
            <p>{{ $description ?? '' }}</p>
        </div>

        <div class="card auth-card">
            <div class="card-body p-4">
                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        @yield('footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
