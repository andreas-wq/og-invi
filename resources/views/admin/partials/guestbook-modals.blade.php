@foreach($messages as $msg)
<!-- Reply Modal -->
<div class="modal fade" id="replyModal{{ $msg['id'] }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.guestbook.reply', $msg['id']) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Balas Ucapan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Menanggapi ucapan dari <strong>{{ $msg['name'] }}</strong>.</p>
                    @if($msg['reply'])
                        <div class="alert alert-warning py-2 small"><i class="bi bi-exclamation-triangle me-1"></i>Menimpa balasan yang sudah ada.</div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Balasan</label>
                        <textarea class="form-control" name="reply" rows="4" placeholder="Tulis balasan untuk tamu…" required maxlength="2000">{{ old('reply', $msg['reply'] ?? '') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Balasan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete Modal -->
<div class="modal fade" id="deleteModal{{ $msg['id'] }}" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="{{ route('admin.guestbook.destroy', $msg['id']) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Ucapan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Ucapan dari <strong>{{ $msg['name'] }}</strong> akan dihapus permanen.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
