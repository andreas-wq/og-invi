@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Ucapan</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="text-primary"><i class="bi bi-chat-heart fs-1"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Konfirmasi Hadir</h6>
                            <h3 class="mb-0 fw-bold text-success">{{ $stats['hadir'] }}</h3>
                        </div>
                        <div class="text-success"><i class="bi bi-people fs-1"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Tidak Hadir</h6>
                            <h3 class="mb-0 fw-bold text-danger">{{ $stats['tidak_hadir'] }}</h3>
                        </div>
                        <div class="text-danger"><i class="bi bi-person-x fs-1"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="bi bi-chat-square-text me-2"></i>Daftar Ucapan Tamu</h5>
        </div>
        <div class="card-body p-0">
            @forelse($messages as $msg)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1 fw-semibold">{{ $msg['name'] }}</h6>
                            <small class="text-muted">{{ $msg['created_at'] }}</small>
                            @if($msg['attendance'])
                                @php $isHadir = $msg['attendance'] === 'Hadir'; @endphp
                                <span class="badge {{ $isHadir ? 'attendance-hadir' : 'attendance-tidak' }} ms-2">{{ $msg['attendance'] }}</span>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#replyModal{{ $msg['id'] }}">
                                <i class="bi bi-reply"></i> {{ $msg['reply'] ? 'Edit' : 'Balas' }}
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $msg['id'] }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mb-2">{{ $msg['message'] }}</p>
                    @if($msg['reply'])
                        <div class="alert alert-light border mb-0 mt-2">
                            <small class="text-muted d-block mb-1"><i class="bi bi-reply-fill me-1"></i>Balasan ({{ $msg['replied_at'] }}):</small>
                            <p class="mb-0">{{ $msg['reply'] }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Belum ada ucapan dari tamu.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@include('admin.partials.guestbook-modals')
@endsection
