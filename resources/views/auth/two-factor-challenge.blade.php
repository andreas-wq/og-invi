@extends('layouts.auth')

@section('content')
<div id="auth-code-section">
    <p class="text-muted small mb-3">Enter the authentication code provided by your authenticator application.</p>

    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf

        <div class="mb-3">
            <label for="code" class="form-label">Authentication code</label>
            <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" required autofocus autocomplete="one-time-code" placeholder="Enter 6-digit code" inputmode="numeric" pattern="[0-9]*" maxlength="6">
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-shield-check me-1"></i> Continue
            </button>
        </div>
    </form>
</div>

<div id="recovery-code-section" style="display: none;">
    <p class="text-muted small mb-3">Please confirm access to your account by entering one of your emergency recovery codes.</p>

    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf

        <div class="mb-3">
            <label for="recovery_code" class="form-label">Recovery code</label>
            <input id="recovery_code" type="text" class="form-control @error('recovery_code') is-invalid @enderror" name="recovery_code" autofocus autocomplete="one-time-code" placeholder="Enter recovery code">
            @error('recovery_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-shield-check me-1"></i> Continue
            </button>
        </div>
    </form>
</div>

<div class="text-center mt-3">
    <button type="button" class="btn btn-link text-decoration-none" id="toggle-recovery">
        Or login using a recovery code
    </button>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('toggle-recovery').addEventListener('click', function() {
    const codeSection = document.getElementById('auth-code-section');
    const recoverySection = document.getElementById('recovery-code-section');
    const toggleBtn = document.getElementById('toggle-recovery');

    if (recoverySection.style.display === 'none') {
        codeSection.style.display = 'none';
        recoverySection.style.display = 'block';
        toggleBtn.textContent = 'Or login using an authentication code';
        document.getElementById('recovery_code').focus();
    } else {
        codeSection.style.display = 'block';
        recoverySection.style.display = 'none';
        toggleBtn.textContent = 'Or login using a recovery code';
        document.getElementById('code').focus();
    }
});
</script>
@endpush
