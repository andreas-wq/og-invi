@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-link-45deg me-2"></i>Buat Link Undangan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="guestName" class="form-label">Nama Tamu</label>
                        <input type="text" class="form-control" id="guestName" placeholder="Masukkan nama tamu…">
                    </div>
                    <div class="mb-3">
                        <label for="guestPhone" class="form-label">Nomor WhatsApp <small class="text-muted">(opsional)</small></label>
                        <input type="text" class="form-control" id="guestPhone" placeholder="cth: 08123456789">
                    </div>
                    <div class="mb-3">
                        <label for="messageTemplate" class="form-label">Template Pesan</label>
                        <textarea class="form-control" id="messageTemplate" rows="8">{{ $defaultTemplate }}</textarea>
                        <div class="form-text">Gunakan <code>{nama}</code> dan <code>{link}</code> sebagai placeholder.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-eye me-2"></i>Preview & Aksi</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link Undangan</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="generatedLink" readonly placeholder="Isi nama tamu untuk membuat link…">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyLink()">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Preview Pesan</label>
                        <div class="border rounded p-3 bg-light" id="messagePreview">
                            <p class="text-muted mb-0">Isi nama tamu untuk melihat preview pesan.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-success" id="waShareBtn" target="_blank" rel="noopener">
                            <i class="bi bi-whatsapp"></i> Bagikan via WA
                        </a>
                        <button class="btn btn-outline-primary" onclick="saveToRecent()">
                            <i class="bi bi-bookmark"></i> Simpan ke Riwayat
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Tamu</h5>
            <small class="text-muted">Nama tamu yang pernah dibuatkan link, tersimpan di perangkat ini.</small>
        </div>
        <div class="card-body">
            <div id="recentList">
                <p class="text-muted text-center py-3">Belum ada riwayat.</p>
            </div>
        </div>
    </div>
</div>
@include('admin.partials.invite-scripts')
@endsection
