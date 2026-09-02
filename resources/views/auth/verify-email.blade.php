@extends('layouts.auth')

@section('content')
<p class="text-muted small mb-3">Please verify your email address by clicking on the link we just emailed to you.</p>

<form method="POST" action="{{ route('verification.send') }}">
    @csrf

    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-envelope-check me-1"></i> Resend verification email
        </button>
    </div>
</form>
@endsection

@section('footer')
<div class="text-center mt-3">
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link text-decoration-none p-0">Log out</button>
    </form>
</div>
@endsection
