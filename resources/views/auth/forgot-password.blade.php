@extends('layouts.auth')

@section('content')
<p class="text-muted small mb-3">Enter your email to receive a password reset link.</p>

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="off" placeholder="email@example.com">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-envelope me-1"></i> Email password reset link
        </button>
    </div>
</form>
@endsection

@section('footer')
<div class="text-center mt-3">
    <span class="text-muted">Or, return to</span> <a class="text-decoration-none" href="{{ route('login') }}">log in</a>
</div>
@endsection
