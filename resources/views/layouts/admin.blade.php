<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin' }} - {{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; }
        .sidebar .nav-link { color: rgba(255,255,255,0.75); padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 0.25rem; }
        .sidebar .nav-link:hover { color: #fff; background-color: rgba(255,255,255,0.1); }
        .sidebar .nav-link.active { color: #fff; background-color: #0d6efd; }
        .sidebar .nav-link i { margin-right: 0.5rem; }
        .sidebar-brand { color: #fff; font-size: 1.25rem; font-weight: 600; padding: 1rem; text-decoration: none; display: block; }
        .content-wrapper { padding: 1.5rem; }
        .stat-card { border: none; border-radius: 0.5rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
        .attendance-hadir { background-color: #d1e7dd; color: #0f5132; }
        .attendance-tidak { background-color: #f8d7da; color: #842029; }
    </style>
</head>
<body>
    <div class="d-flex">
        <nav class="sidebar d-flex flex-column" style="width: 250px;">
            <a href="{{ route('admin.guestbook.index') }}" class="sidebar-brand">
                <i class="bi bi-shield-lock"></i> Admin Panel
            </a>
            <hr class="text-white-50 mx-3">
            <ul class="nav flex-column px-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.guestbook.*') ? 'active' : '' }}" href="{{ route('admin.guestbook.index') }}">
                        <i class="bi bi-chat-heart"></i> Moderasi Ucapan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.invite.*') ? 'active' : '' }}" href="{{ route('admin.invite') }}">
                        <i class="bi bi-send"></i> Kirim Undangan
                    </a>
                </li>
            </ul>
            <hr class="text-white-50 mx-3">
            <ul class="nav flex-column px-2">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="bi bi-arrow-left"></i> Kembali ke Undangan
                    </a>
                </li>
            </ul>
            <div class="mt-auto px-3 pb-3">
                <div class="d-flex align-items-center text-white-50">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    <span class="small">{{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
            </div>
        </nav>

        <main style="flex: 1;">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1 fw-semibold">{{ $title ?? 'Dashboard' }}</span>
                    <div class="d-flex align-items-center ms-auto">
                        <span class="text-muted me-3 small">{{ Auth::user()->email ?? '' }}</span>
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

    @yield('modals')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
