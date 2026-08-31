{{--
    resources/views/partials/guestbook.blade.php

    Section "Doa & Ucapan" — mandiri, tidak butuh Tailwind/DaisyUI.
    Kompatibel dengan GuestbookController:
    - GET  /guestbook/messages?page=N  -> index()
    - POST /guestbook                  -> store()

    Cara pakai di halaman undangan:
        @include('partials.guestbook')

    Butuh Alpine.js ter-load global, dan meta csrf-token di <head>:
        <meta name="csrf-token" content="{{ csrf_token() }}">
--}}

<section
    id="doa-ucapan"
    class="gb-section"
    x-data="guestbook({
        initialMessages: {{ Js::from($messages->items()) }},
        initialCounts: {{ Js::from($counts) }},
        currentPage: {{ $messages->currentPage() }},
        lastPage: {{ $messages->lastPage() }},
        initialName: {{ Js::from(request()->query('to', '')) }},
    })"
>
    <div class="gb-wrap">

        {{-- Heading --}}
        <div class="gb-heading">
            <p class="gb-eyebrow">Wedding Wishes</p>
            <h2 class="gb-title">Doa &amp; Ucapan</h2>
            <svg class="gb-divider" viewBox="0 0 64 16" fill="none">
                <path d="M2 8c8-10 16 10 24 0s16-10 24 0 10-6 12-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </div>

        {{-- Counts --}}
        <div class="gb-counts">
            <div class="gb-count-card gb-count-hadir">
                <span class="gb-count-num" x-text="counts.hadir ?? 0"></span>
                <span class="gb-count-label">Hadir</span>
            </div>
            <div class="gb-count-card gb-count-tidak">
                <span class="gb-count-num" x-text="counts.tidak_hadir ?? 0"></span>
                <span class="gb-count-label">Tidak Hadir</span>
            </div>
        </div>

        {{-- Form --}}
        <form @submit.prevent="submit" class="gb-form">
            <div class="gb-field">
                <label class="gb-label">Nama</label>
                <input
                    type="text"
                    x-model="form.name"
                    placeholder="Nama Anda"
                    maxlength="100"
                    required
                    class="gb-input"
                />
            </div>

            <div class="gb-field">
                <label class="gb-label">Ucapan &amp; Doa</label>
                <textarea
                    x-model="form.message"
                    rows="3"
                    minlength="2"
                    maxlength="1000"
                    placeholder="Tuliskan doa & ucapan terbaik Anda..."
                    required
                    class="gb-textarea"
                ></textarea>
            </div>

            <div class="gb-field">
                <label class="gb-label">Konfirmasi Kehadiran</label>
                <div class="gb-attend-group">
                    <button
                        type="button"
                        @click="form.attendance = 'Hadir'"
                        :class="{ 'is-active-hadir': form.attendance === 'Hadir' }"
                        class="gb-attend-btn"
                    >
                        Hadir
                    </button>
                    <button
                        type="button"
                        @click="form.attendance = 'Tidak hadir'"
                        :class="{ 'is-active-tidak': form.attendance === 'Tidak hadir' }"
                        class="gb-attend-btn"
                    >
                        Tidak Hadir
                    </button>
                </div>
            </div>

            <template x-if="errorMessage">
                <p class="gb-error" x-text="errorMessage"></p>
            </template>

            <button type="submit" :disabled="submitting" class="gb-submit">
                <svg x-show="submitting" class="gb-spinner" viewBox="0 0 24 24" fill="none">
                    <circle class="gb-spinner-track" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="gb-spinner-head" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span x-text="submitting ? 'Mengirim...' : 'Kirim Ucapan'"></span>
            </button>
        </form>

        {{-- Toast --}}
        <div x-show="toast" x-transition x-cloak class="gb-toast" x-text="toast"></div>

        {{-- List --}}
        <div class="gb-list">
            <template x-if="messages.length === 0">
                <p class="gb-empty">Jadilah yang pertama mengirim doa &amp; ucapan.</p>
            </template>

            <template x-for="item in messages" :key="item.id">
                <div class="gb-item">
                    <div class="gb-avatar" x-text="initials(item.name)"></div>

                    <div class="gb-item-body">
                        <div class="gb-item-head">
                            <p class="gb-item-name" x-text="item.name"></p>
                            <span
                                x-show="item.attendance"
                                :class="item.attendance === 'Hadir' ? 'gb-badge-hadir' : 'gb-badge-tidak'"
                                class="gb-badge"
                                x-text="item.attendance"
                            ></span>
                        </div>
                        <p class="gb-item-message" x-text="item.message"></p>
                        <p class="gb-item-time" x-text="item.time"></p>

                        <template x-if="item.reply">
                            <div class="gb-reply">
                                <p class="gb-reply-label">Balasan mempelai</p>
                                <p class="gb-reply-text" x-text="item.reply"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        {{-- Load more --}}
        <div class="gb-load-more" x-show="currentPage < lastPage">
            <button @click="loadMore" :disabled="loadingMore" class="gb-load-more-btn">
                <span x-text="loadingMore ? 'Memuat...' : 'Muat ucapan lainnya'"></span>
            </button>
        </div>
    </div>
</section>

<style>
    #doa-ucapan.gb-section {
        --gb-gold: #b8935f;
        --gb-gold-light: #f4ead9;
        --gb-ink: #3a352f;
        --gb-muted: #8a8177;
        --gb-green: #4a8b6f;
        --gb-green-bg: #eaf5ef;
        --gb-grey-bg: #f2efe9;
        font-family: "Georgia", "Times New Roman", serif;
        padding: 64px 20px;
        background: #fffdf9;
        /* paksa menang atas style Elementor (text-align/flex bawaan tema) */
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        text-align: center !important;
        display: block !important;
        float: none !important;
    }

    #doa-ucapan.gb-section * { box-sizing: border-box; }

    .gb-wrap {
        max-width: 560px !important;
        width: 100% !important;
        margin: 0 auto !important;
        text-align: left !important;
        float: none !important;
    }

    .gb-heading { text-align: center; margin-bottom: 32px; }
    .gb-eyebrow {
        font-family: Arial, sans-serif;
        font-size: 12px;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: var(--gb-gold);
        margin: 0 0 8px;
        font-weight: 600;
    }
    .gb-title {
        font-size: 32px;
        color: var(--gb-ink);
        margin: 0;
        font-weight: 400;
    }
    .gb-divider { width: 64px; height: 16px; color: var(--gb-gold); margin-top: 12px; }

    .gb-counts {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 32px;
        font-family: Arial, sans-serif;
    }
    .gb-count-card {
        border-radius: 16px;
        padding: 16px 8px;
        text-align: center;
        border: 1px solid transparent;
    }
    .gb-count-hadir { background: var(--gb-green-bg); border-color: #d3ecdf; }
    .gb-count-tidak { background: var(--gb-grey-bg); border-color: #e5e0d5; }
    .gb-count-num { display: block; font-size: 24px; font-weight: 700; color: var(--gb-green); }
    .gb-count-tidak .gb-count-num { color: var(--gb-muted); }
    .gb-count-label {
        display: block;
        font-size: 11px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--gb-muted);
        margin-top: 4px;
    }

    .gb-form {
        background: #fff;
        border: 1px solid var(--gb-gold-light);
        border-radius: 20px;
        padding: 24px 20px;
        margin-bottom: 40px;
        box-shadow: 0 4px 20px rgba(184, 147, 95, 0.08);
        font-family: Arial, sans-serif;
    }
    .gb-field { margin-bottom: 16px; }
    .gb-field:last-of-type { margin-bottom: 0; }
    .gb-label {
        display: block;
        font-size: 12px;
        color: var(--gb-muted);
        margin-bottom: 6px;
        font-weight: 600;
    }
    .gb-input, .gb-textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #e5e0d5;
        border-radius: 12px;
        padding: 11px 14px;
        font-size: 14px;
        color: var(--gb-ink);
        font-family: inherit;
        background: #fffdf9;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .gb-textarea { resize: none; }
    .gb-input:focus, .gb-textarea:focus {
        outline: none;
        border-color: var(--gb-gold);
        box-shadow: 0 0 0 3px rgba(184, 147, 95, 0.15);
    }

    .gb-attend-group { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .gb-attend-btn {
        border: 1px solid #e5e0d5;
        background: #fff;
        border-radius: 12px;
        padding: 10px;
        font-size: 14px;
        font-weight: 600;
        color: var(--gb-ink);
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }
    .gb-attend-btn.is-active-hadir { background: var(--gb-green); border-color: var(--gb-green); color: #fff; }
    .gb-attend-btn.is-active-tidak { background: #8a8177; border-color: #8a8177; color: #fff; }

    .gb-error {
        font-size: 12px;
        color: #b23b3b;
        background: #fbeaea;
        border: 1px solid #f2d3d3;
        border-radius: 10px;
        padding: 8px 12px;
        margin-top: 14px;
    }

    .gb-submit {
        width: 100%;
        margin-top: 18px;
        border: none;
        border-radius: 12px;
        background: var(--gb-gold);
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        padding: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background 0.2s, opacity 0.2s;
        font-family: inherit;
    }
    .gb-submit:hover:not(:disabled) { background: #a37e4d; }
    .gb-submit:disabled { opacity: 0.6; cursor: not-allowed; }
    .gb-spinner { width: 16px; height: 16px; animation: gb-spin 0.8s linear infinite; }
    .gb-spinner-track { opacity: 0.3; }
    @keyframes gb-spin { to { transform: rotate(360deg); } }

    .gb-toast {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 999;
        background: var(--gb-ink);
        color: #fff;
        font-family: Arial, sans-serif;
        font-size: 13px;
        padding: 10px 18px;
        border-radius: 999px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    }

    .gb-list { display: flex; flex-direction: column; gap: 14px; font-family: Arial, sans-serif; }
    .gb-empty {
        text-align: center;
        font-size: 13px;
        color: var(--gb-muted);
        padding: 32px 0;
    }
    .gb-item {
        display: flex;
        gap: 12px;
        background: #fff;
        border: 1px solid #f0ebe0;
        border-radius: 16px;
        padding: 16px;
    }
    .gb-avatar {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f4ead9, #e6cfa8);
        color: var(--gb-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
    }
    .gb-item-body { min-width: 0; flex: 1; }
    .gb-item-head { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
    .gb-item-name { font-size: 14px; font-weight: 700; color: var(--gb-ink); margin: 0; }
    .gb-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 9px;
        border-radius: 999px;
    }
    .gb-badge-hadir { background: var(--gb-green-bg); color: var(--gb-green); }
    .gb-badge-tidak { background: var(--gb-grey-bg); color: var(--gb-muted); }
    .gb-item-message {
        font-size: 13.5px;
        color: #55504a;
        margin: 6px 0 0;
        white-space: pre-line;
        word-break: break-word;
        line-height: 1.5;
    }
    .gb-item-time { font-size: 11px; color: #b5ada0; margin: 6px 0 0; }

    .gb-reply {
        margin-top: 10px;
        margin-left: 6px;
        padding-left: 12px;
        border-left: 2px solid var(--gb-gold-light);
    }
    .gb-reply-label { font-size: 11px; font-weight: 700; color: var(--gb-gold); margin: 0; }
    .gb-reply-text { font-size: 13px; color: #55504a; margin: 2px 0 0; }

    .gb-load-more { text-align: center; margin-top: 24px; font-family: Arial, sans-serif; }
    .gb-load-more-btn {
        background: none;
        border: none;
        color: var(--gb-gold);
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }
    .gb-load-more-btn:hover:not(:disabled) { color: #a37e4d; }
    .gb-load-more-btn:disabled { opacity: 0.6; cursor: not-allowed; }
</style>

<script>
    function guestbook({ initialMessages, initialCounts, currentPage, lastPage, initialName }) {
        return {
            messages: initialMessages ?? [],
            counts: initialCounts ?? { hadir: 0, tidak_hadir: 0 },
            currentPage,
            lastPage,
            loadingMore: false,
            submitting: false,
            errorMessage: '',
            toast: '',
            form: { name: initialName || '', message: '', attendance: '' },

            csrfToken() {
                const meta = document.querySelector('meta[name="csrf-token"]');
                return meta ? meta.getAttribute('content') : '';
            },

            initials(name) {
                if (!name) return '?';
                return name.trim().split(/\s+/).slice(0, 2).map(w => w[0]).join('').toUpperCase();
            },

            showToast(text) {
                this.toast = text;
                setTimeout(() => (this.toast = ''), 3000);
            },

            async submit() {
                this.errorMessage = '';

                if (this.form.name.trim().length < 1) {
                    this.errorMessage = 'Mohon isi nama Anda terlebih dahulu.';
                    return;
                }
                if (this.form.message.trim().length < 2) {
                    this.errorMessage = 'Ucapan minimal 2 karakter.';
                    return;
                }

                this.submitting = true;

                try {
                    const res = await fetch('{{ route('guestbook.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken(),
                        },
                        body: JSON.stringify(this.form),
                    });

                    const body = await res.json();

                    if (!res.ok) {
                        const errors = body.errors ?? {};
                        const firstKey = Object.keys(errors)[0];
                        this.errorMessage = firstKey ? errors[firstKey][0] : 'Gagal mengirim. Silakan coba lagi.';
                        return;
                    }

                    this.messages.unshift(body.data);
                    this.counts = body.counts ?? this.counts;
                    this.form = { name: '', message: '', attendance: '' };
                    this.showToast(body.message ?? 'Terima kasih!');
                } catch (e) {
                    this.errorMessage = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                } finally {
                    this.submitting = false;
                }
            },

            async loadMore() {
                if (this.currentPage >= this.lastPage || this.loadingMore) return;
                this.loadingMore = true;

                try {
                    const nextPage = this.currentPage + 1;
                    const res = await fetch(`{{ url('/guestbook/messages') }}?page=${nextPage}`, {
                        headers: { Accept: 'application/json' },
                    });
                    const body = await res.json();

                    if (body.status === 'success') {
                        this.messages.push(...body.data);
                        this.currentPage = body.meta.current_page;
                        this.lastPage = body.meta.last_page;
                        this.counts = body.counts ?? this.counts;
                    }
                } finally {
                    this.loadingMore = false;
                }
            },
        };
    }
</script>