<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Settings' }} - {{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .settings-sidebar { min-height: 100vh; background-color: #fff; border-right: 1px solid #e9ecef; width: 240px; }
        .settings-sidebar .nav-link { color: #495057; padding: 0.6rem 1rem; border-radius: 0.375rem; margin-bottom: 0.125rem; }
        .settings-sidebar .nav-link:hover { background-color: #f8f9fa; }
        .settings-sidebar .nav-link.active { color: #0d6efd; background-color: #e7f1ff; }
        .settings-sidebar .nav-link i { margin-right: 0.5rem; }
        .settings-brand { color: #212529; font-size: 1.1rem; font-weight: 600; padding: 1rem; text-decoration: none; display: block; }
        .content-wrapper { padding: 1.5rem; }
    </style>
</head>
<body>
    <div class="d-flex">
        <nav class="settings-sidebar d-flex flex-column">
            <a href="{{ route('profile.edit') }}" class="settings-brand">
                <i class="bi bi-gear"></i> Settings
            </a>
            <hr class="my-2 mx-3">
            <ul class="nav flex-column px-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('security.*') ? 'active' : '' }}" href="{{ route('security.edit') }}">
                        <i class="bi bi-shield-lock"></i> Security
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('appearance.*') ? 'active' : '' }}" href="{{ route('appearance.edit') }}">
                        <i class="bi bi-palette"></i> Appearance
                    </a>
                </li>
            </ul>
            <hr class="my-2 mx-3">
            <ul class="nav flex-column px-2">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.guestbook.index') }}">
                        <i class="bi bi-arrow-left"></i> Back to Admin
                    </a>
                </li>
            </ul>
            <div class="mt-auto px-3 pb-3">
                <div class="d-flex align-items-center text-muted">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    <span class="small">{{ Auth::user()->name ?? 'User' }}</span>
                </div>
            </div>
        </nav>

        <main style="flex: 1;">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1 fw-semibold">{{ $title ?? 'Settings' }}</span>
                    <div class="d-flex align-items-center ms-auto">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <div class="content-wrapper">
                @if(session('toast'))
                    <div class="alert alert-{{ session('toast.type', 'success') }} alert-dismissible fade show" role="alert">
                        {{ session('toast.message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('status'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
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
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
