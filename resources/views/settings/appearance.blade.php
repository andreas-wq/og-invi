@extends('layouts.settings')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-palette me-2"></i>Appearance</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Choose how the application looks to you.</p>

                <div class="row g-3">
                    <div class="col-4">
                        <form method="POST" action="{{ route('appearance.update') }}">
                            @csrf
                            <input type="hidden" name="appearance" value="light">
                            <button type="submit" class="btn btn-outline-primary w-100 py-3 {{ ($appearance ?? 'system') === 'light' ? 'active' : '' }}">
                                <i class="bi bi-sun fs-4 d-block mb-2"></i>
                                <span class="d-block">Light</span>
                            </button>
                        </form>
                    </div>
                    <div class="col-4">
                        <form method="POST" action="{{ route('appearance.update') }}">
                            @csrf
                            <input type="hidden" name="appearance" value="dark">
                            <button type="submit" class="btn btn-outline-dark w-100 py-3 {{ ($appearance ?? 'system') === 'dark' ? 'active' : '' }}">
                                <i class="bi bi-moon fs-4 d-block mb-2"></i>
                                <span class="d-block">Dark</span>
                            </button>
                        </form>
                    </div>
                    <div class="col-4">
                        <form method="POST" action="{{ route('appearance.update') }}">
                            @csrf
                            <input type="hidden" name="appearance" value="system">
                            <button type="submit" class="btn btn-outline-secondary w-100 py-3 {{ ($appearance ?? 'system') === 'system' ? 'active' : '' }}">
                                <i class="bi bi-display fs-4 d-block mb-2"></i>
                                <span class="d-block">System</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
