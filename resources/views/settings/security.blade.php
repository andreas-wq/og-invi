@extends('layouts.settings')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Password -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-key me-2"></i>Update Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user-password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required autocomplete="current-password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Update Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Two Factor -->
        @if($canManageTwoFactor)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-shield-check me-2"></i>Two-Factor Authentication</h5>
            </div>
            <div class="card-body">
                @if($twoFactorEnabled)
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-1"></i> Two-factor authentication is enabled.
                    </div>
                    @if($requiresConfirmation && !$user->two_factor_confirmed_at)
                        <p class="text-warning">Please finish configuring your two-factor authentication.</p>
                    @endif
                    <form method="POST" action="{{ route('two-factor.disable') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle me-1"></i> Disable
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-1"></i> Two-factor authentication is not enabled.
                    </div>
                    <form method="POST" action="{{ route('two-factor.enable') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-shield-plus me-1"></i> Enable
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endif

        <!-- Passkeys -->
        @if($canManagePasskeys)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-passkey me-2"></i>Passkeys</h5>
            </div>
            <div class="card-body">
                @if(count($passkeys) > 0)
                    <ul class="list-group">
                        @foreach($passkeys as $passkey)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $passkey['name'] }}</strong>
                                    <br><small class="text-muted">Added {{ $passkey['created_at_diff'] }}</small>
                                </div>
                                <form method="POST" action="{{ route('passkeys.destroy', $passkey['id']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No passkeys registered.</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
