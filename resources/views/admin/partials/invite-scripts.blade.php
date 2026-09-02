<script>
const invitationUrl = @json($invitationUrl);
const STORAGE_KEY = 'undangan.recent-guests';
let recentGuests = loadRecent();

function loadRecent() {
    try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; } catch { return []; }
}
function saveRecent() { localStorage.setItem(STORAGE_KEY, JSON.stringify(recentGuests)); }
function buildLink(name) {
    try { const url = new URL(invitationUrl); url.searchParams.set('to', name.trim()); return url.toString(); } catch { return invitationUrl + '?to=' + encodeURIComponent(name.trim()); }
}
function normalizePhone(phone) {
    const digits = phone.replace(/[^0-9]/g, '');
    if (digits.startsWith('0')) return '62' + digits.slice(1);
    if (digits.startsWith('62')) return digits;
    return digits ? '62' + digits : '';
}
function buildMessage(name, link) {
    const template = document.getElementById('messageTemplate').value;
    return template.replaceAll('{nama}', name.trim()).replaceAll('{link}', link);
}
function escapeHtml(text) { const div = document.createElement('div'); div.textContent = text; return div.innerHTML; }
function updatePreview() {
    const name = document.getElementById('guestName').value;
    const linkInput = document.getElementById('generatedLink');
    const preview = document.getElementById('messagePreview');
    const waBtn = document.getElementById('waShareBtn');
    if (!name.trim()) { linkInput.value = ''; preview.innerHTML = '<p class="text-muted mb-0">Isi nama tamu untuk melihat preview pesan.</p>'; waBtn.href = '#'; return; }
    const link = buildLink(name); linkInput.value = link;
    const message = buildMessage(name, link);
    preview.innerHTML = '<p class="mb-0" style="white-space: pre-wrap;">' + escapeHtml(message) + '</p>';
    const phone = document.getElementById('guestPhone').value;
    const normalized = normalizePhone(phone);
    waBtn.href = normalized ? 'https://api.whatsapp.com/send?phone=' + normalized + '&text=' + encodeURIComponent(message) : 'https://wa.me/?text=' + encodeURIComponent(message);
}
function copyLink() {
    const link = document.getElementById('generatedLink').value; if (!link) return;
    navigator.clipboard.writeText(link).then(() => { const btn = document.querySelector('#generatedLink + .btn'); btn.innerHTML = '<i class="bi bi-check"></i> Copied!'; setTimeout(() => { btn.innerHTML = '<i class="bi bi-clipboard"></i> Copy'; }, 2000); });
}
function saveToRecent() {
    const name = document.getElementById('guestName').value.trim(); const phone = document.getElementById('guestPhone').value.trim(); if (!name) return;
    recentGuests = [{ name, phone, createdAt: new Date().toISOString() }, ...recentGuests.filter(g => g.name !== name)].slice(0, 15); saveRecent(); renderRecent();
}
function removeFromRecent(name) { recentGuests = recentGuests.filter(g => g.name !== name); saveRecent(); renderRecent(); }
function shareFromRecent(name, phone) {
    const link = buildLink(name); const message = buildMessage(name, link);
    const normalized = normalizePhone(phone);
    window.open(normalized ? 'https://api.whatsapp.com/send?phone=' + normalized + '&text=' + encodeURIComponent(message) : 'https://wa.me/?text=' + encodeURIComponent(message), '_blank', 'noopener');
}
function renderRecent() {
    const container = document.getElementById('recentList');
    if (recentGuests.length === 0) { container.innerHTML = '<p class="text-muted text-center py-3">Belum ada riwayat.</p>'; return; }
    container.innerHTML = recentGuests.map(g => {
        return '<div class="d-flex align-items-center justify-content-between p-2 border-bottom">' +
            '<div style="min-width: 0; overflow: hidden;"><p class="mb-0 fw-medium text-truncate">' + escapeHtml(g.name) + '</p><small class="text-muted text-truncate d-block">' + escapeHtml(g.phone || 'Tanpa nomor WA') + '</small></div>' +
            '<div class="d-flex gap-1 ms-2 flex-shrink-0">' +
                '<button class="btn btn-sm btn-outline-success" onclick="shareFromRecent(\'' + escapeHtml(g.name).replace(/'/g, "\\'") + '\', \'' + escapeHtml(g.phone).replace(/'/g, "\\'") + '\')"><i class="bi bi-whatsapp"></i></button>' +
                '<button class="btn btn-sm btn-outline-danger" onclick="removeFromRecent(\'' + escapeHtml(g.name).replace(/'/g, "\\'") + '\')"><i class="bi bi-trash"></i></button>' +
            '</div></div>';
    }).join('');
}
document.getElementById('guestName').addEventListener('input', updatePreview);
document.getElementById('guestPhone').addEventListener('input', updatePreview);
document.getElementById('messageTemplate').addEventListener('input', updatePreview);
renderRecent();
</script>
