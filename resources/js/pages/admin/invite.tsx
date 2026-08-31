import { Head } from '@inertiajs/react';
import { useEffect, useMemo, useState } from 'react';
import { Copy, MessageCircle, Trash2 } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { dashboard } from '@/routes';

type RecentGuest = {
    name: string;
    phone: string;
    createdAt: string;
};

const STORAGE_KEY = 'undangan.recent-guests';

const DEFAULT_TEMPLATE = `Assalamu'alaikum Bapak/Ibu/Saudara/i {nama},

Tanpa mengurangi rasa hormat, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk hadir di acara pernikahan kami.

Berikut link undangan digital kami, mohon buka untuk info lengkap acara:
{link}

Merupakan suatu kehormatan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu.

Terima kasih banyak 🙏`;

function buildLink(invitationUrl: string, name: string): string {
    const url = new URL(invitationUrl);
    url.searchParams.set('to', name.trim());
    return url.toString();
}

function normalizePhone(phone: string): string {
    const digits = phone.replace(/[^0-9]/g, '');
    if (digits.startsWith('0')) {
        return '62' + digits.slice(1);
    }
    if (digits.startsWith('62')) {
        return digits;
    }
    return digits ? '62' + digits : '';
}

export default function AdminInvite({ invitationUrl }: { invitationUrl: string }) {
    const [name, setName] = useState('');
    const [phone, setPhone] = useState('');
    const [template, setTemplate] = useState(DEFAULT_TEMPLATE);
    const [recent, setRecent] = useState<RecentGuest[]>([]);
    const [copied, setCopied] = useState<'link' | 'message' | null>(null);

    useEffect(() => {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            try {
                setRecent(JSON.parse(saved));
            } catch {
                // ignore corrupt storage
            }
        }
    }, []);

    const link = useMemo(
        () => (name.trim() ? buildLink(invitationUrl, name) : ''),
        [invitationUrl, name],
    );

    const message = useMemo(() => {
        if (!name.trim()) return '';
        return template.replaceAll('{nama}', name.trim()).replaceAll('{link}', link);
    }, [template, name, link]);

    const saveToRecent = (guestName: string, guestPhone: string) => {
        const next = [
            { name: guestName, phone: guestPhone, createdAt: new Date().toISOString() },
            ...recent.filter((g) => g.name !== guestName),
        ].slice(0, 15);
        setRecent(next);
        localStorage.setItem(STORAGE_KEY, JSON.stringify(next));
    };

    const removeFromRecent = (guestName: string) => {
        const next = recent.filter((g) => g.name !== guestName);
        setRecent(next);
        localStorage.setItem(STORAGE_KEY, JSON.stringify(next));
    };

    const handleShare = (guestName: string, guestPhone: string, guestLink: string) => {
        const text = template
            .replaceAll('{nama}', guestName)
            .replaceAll('{link}', guestLink);
        const normalizedPhone = normalizePhone(guestPhone);

        const waUrl = normalizedPhone
            ? `https://wa.me/${normalizedPhone}?text=${encodeURIComponent(text)}`
            : `https://wa.me/?text=${encodeURIComponent(text)}`;

        saveToRecent(guestName, guestPhone);
        window.open(waUrl, '_blank');
    };

    const copyToClipboard = async (text: string, kind: 'link' | 'message') => {
        await navigator.clipboard.writeText(text);
        setCopied(kind);
        setTimeout(() => setCopied(null), 1500);
    };

    return (
        <>
            <Head title="Kirim Undangan" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="grid gap-4 lg:grid-cols-2">
                    {/* Form */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Buat Link &amp; Pesan Undangan</CardTitle>
                            <CardDescription>
                                Masukkan nama tamu, link undangan personal dan pesan WhatsApp
                                akan dibuat otomatis.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="flex flex-col gap-4">
                            <div className="flex flex-col gap-2">
                                <Label htmlFor="guest-name">Nama Tamu</Label>
                                <Input
                                    id="guest-name"
                                    placeholder="Contoh: Budi Santoso"
                                    value={name}
                                    onChange={(e) => setName(e.target.value)}
                                />
                            </div>

                            <div className="flex flex-col gap-2">
                                <Label htmlFor="guest-phone">
                                    Nomor WhatsApp{' '}
                                    <span className="text-muted-foreground font-normal">
                                        (opsional — kosongkan untuk pilih kontak manual)
                                    </span>
                                </Label>
                                <Input
                                    id="guest-phone"
                                    placeholder="08xx atau 62xx"
                                    value={phone}
                                    onChange={(e) => setPhone(e.target.value)}
                                />
                            </div>

                            <div className="flex flex-col gap-2">
                                <Label htmlFor="template">
                                    Template Pesan{' '}
                                    <span className="text-muted-foreground font-normal">
                                        (gunakan {'{nama}'} dan {'{link}'})
                                    </span>
                                </Label>
                                <Textarea
                                    id="template"
                                    rows={8}
                                    value={template}
                                    onChange={(e) => setTemplate(e.target.value)}
                                />
                            </div>

                            {link && (
                                <div className="flex flex-col gap-2 rounded-md bg-neutral-100 p-3 dark:bg-neutral-900">
                                    <div className="flex items-center justify-between gap-2">
                                        <p className="truncate text-sm">{link}</p>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            onClick={() => copyToClipboard(link, 'link')}
                                            aria-label="Salin link"
                                        >
                                            <Copy className="size-4" />
                                        </Button>
                                    </div>
                                    {copied === 'link' && (
                                        <p className="text-xs text-emerald-600">Link disalin!</p>
                                    )}
                                </div>
                            )}

                            <div className="flex gap-2">
                                <Button
                                    variant="outline"
                                    className="flex-1"
                                    disabled={!message}
                                    onClick={() => copyToClipboard(message, 'message')}
                                >
                                    <Copy className="size-4" />
                                    Salin Pesan
                                </Button>
                                <Button
                                    className="flex-1 bg-emerald-600 hover:bg-emerald-700"
                                    disabled={!name.trim()}
                                    onClick={() => handleShare(name.trim(), phone, link)}
                                >
                                    <MessageCircle className="size-4" />
                                    Bagikan ke WhatsApp
                                </Button>
                            </div>
                            {copied === 'message' && (
                                <p className="-mt-2 text-xs text-emerald-600">Pesan disalin!</p>
                            )}
                        </CardContent>
                    </Card>

                    {/* Preview */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Preview Pesan</CardTitle>
                            <CardDescription>
                                Tampilan pesan yang akan dikirim ke tamu.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            {message ? (
                                <div className="rounded-lg bg-[#e7ffdb] p-4 text-sm whitespace-pre-wrap dark:bg-emerald-950/40">
                                    {message}
                                </div>
                            ) : (
                                <p className="text-muted-foreground py-8 text-center text-sm">
                                    Isi nama tamu untuk melihat preview pesan.
                                </p>
                            )}
                        </CardContent>
                    </Card>
                </div>

                {/* Recent */}
                <Card>
                    <CardHeader>
                        <CardTitle>Riwayat Tamu</CardTitle>
                        <CardDescription>
                            Nama tamu yang pernah dibuatkan link, tersimpan di perangkat ini.
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="flex flex-col gap-2">
                        {recent.length === 0 && (
                            <p className="text-muted-foreground py-4 text-center text-sm">
                                Belum ada riwayat.
                            </p>
                        )}
                        {recent.map((guest) => {
                            const guestLink = buildLink(invitationUrl, guest.name);
                            return (
                                <div
                                    key={guest.name}
                                    className="flex items-center justify-between gap-2 rounded-md border p-3"
                                >
                                    <div className="min-w-0">
                                        <p className="truncate font-medium">{guest.name}</p>
                                        <p className="text-muted-foreground truncate text-xs">
                                            {guest.phone || 'Tanpa nomor WA'}
                                        </p>
                                    </div>
                                    <div className="flex shrink-0 gap-1">
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            className="text-emerald-600 hover:bg-emerald-50 hover:text-emerald-700"
                                            onClick={() =>
                                                handleShare(guest.name, guest.phone, guestLink)
                                            }
                                            aria-label={`Bagikan ke ${guest.name}`}
                                        >
                                            <MessageCircle className="size-4" />
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            className="text-red-500 hover:bg-red-50 hover:text-red-600"
                                            onClick={() => removeFromRecent(guest.name)}
                                            aria-label={`Hapus ${guest.name} dari riwayat`}
                                        >
                                            <Trash2 className="size-4" />
                                        </Button>
                                    </div>
                                </div>
                            );
                        })}
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

AdminInvite.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: dashboard() },
        { title: 'Kirim Undangan', href: '/admin/invite' },
    ],
};