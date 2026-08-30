import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { MessageSquareHeart, MessageSquareText, Trash2, UserX, Users } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { destroy, reply as replyRoute } from '@/routes/admin/guestbook';
import { dashboard } from '@/routes';

type MessageItem = {
    id: number;
    name: string;
    message: string;
    attendance: string | null;
    reply: string | null;
    replied_at: string | null;
    time: string | null;
    created_at: string | null;
};

type Stats = {
    total: number;
    hadir: number;
    tidak_hadir: number;
};

export default function AdminGuestbook({
    messages,
    stats,
}: {
    messages: MessageItem[];
    stats: Stats;
}) {
    const [toDelete, setToDelete] = useState<MessageItem | null>(null);
    const [toReply, setToReply] = useState<MessageItem | null>(null);
    const [replyText, setReplyText] = useState('');

    const openReply = (item: MessageItem) => {
        setToReply(item);
        setReplyText(item.reply ?? '');
    };

    const handleDelete = () => {
        if (!toDelete) {
            return;
        }

        router.delete(destroy(toDelete.id), {
            preserveScroll: true,
            onSuccess: () => setToDelete(null),
        });
    };

    const handleReply = () => {
        if (!toReply) {
            return;
        }

        router.post(
            replyRoute(toReply.id),
            { reply: replyText },
            {
                preserveScroll: true,
                onSuccess: () => {
                    setToReply(null);
                    setReplyText('');
                },
            },
        );
    };

    const statCards = [
        { title: 'Total Ucapan', value: stats.total, icon: MessageSquareHeart },
        { title: 'Konfirmasi Hadir', value: stats.hadir, icon: Users },
        { title: 'Tidak Hadir', value: stats.tidak_hadir, icon: UserX },
    ];
    return (
        <>
            <Head title="Moderasi Ucapan" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    {statCards.map((stat) => (
                        <Card key={stat.title}>
                            <CardHeader>
                                <CardDescription className="flex items-center gap-2">
                                    <stat.icon className="size-4" />
                                    {stat.title}
                                </CardDescription>
                                <CardTitle className="text-3xl tabular-nums">
                                    {stat.value}
                                </CardTitle>
                            </CardHeader>
                        </Card>
                    ))}
                </div>
                <Card>
                    <CardHeader>
                        <CardTitle>Daftar Ucapan Tamu</CardTitle>
                        <CardDescription>
                            Kelola dan balas ucapan yang masuk melalui buku tamu undangan.
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="flex flex-col gap-4">
                        {messages.length === 0 && (
                            <p className="text-muted-foreground py-8 text-center text-sm">
                                Belum ada ucapan yang masuk.
                            </p>
                        )}

                        {messages.map((item) => (
                            <div key={item.id} className="rounded-lg border">
                                <div className="flex items-start gap-3 p-4">
                                    <div className="flex size-10 shrink-0 items-center justify-center rounded-full bg-neutral-200 text-sm font-semibold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                                        {item.name.charAt(0).toUpperCase()}
                                    </div>
                                    <div className="min-w-0 flex-1">
                                        <div className="flex flex-wrap items-center gap-2">
                                            <span className="font-medium">{item.name}</span>
                                            {item.attendance && (
                                                <Badge
                                                    variant="outline"
                                                    className={
                                                        item.attendance === 'Hadir'
                                                            ? 'border-emerald-300 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300'
                                                            : 'border-neutral-300 bg-neutral-50 text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300'
                                                    }
                                                >
                                                    {item.attendance}
                                                </Badge>
                                            )}
                                            {item.created_at && (
                                                <span className="text-muted-foreground text-xs">
                                                    {item.created_at} · {item.time}
                                                </span>
                                            )}
                                        </div>
                                        <p className="mt-1 text-sm whitespace-pre-wrap">
                                            {item.message}
                                        </p>

                                        {item.reply && (
                                            <div className="mt-3 rounded-md border-l-4 border-blue-400 bg-blue-50 p-3 dark:border-blue-700 dark:bg-blue-950/40">
                                                <div className="mb-1 flex items-center gap-1.5 text-xs font-medium text-blue-700 dark:text-blue-300">
                                                    <MessageSquareText className="size-3.5" />
                                                    Balasan Anda
                                                    {item.replied_at && (
                                                        <span className="text-blue-500 dark:text-blue-400">
                                                            · {item.replied_at}
                                                        </span>
                                                    )}
                                                </div>
                                                <p className="text-sm whitespace-pre-wrap text-blue-800 dark:text-blue-200">
                                                    {item.reply}
                                                </p>
                                            </div>
                                        )}
                                    </div>
                                    <div className="flex shrink-0 gap-1">
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            className="text-blue-600 hover:bg-blue-50 hover:text-blue-700 dark:text-blue-400 dark:hover:bg-blue-950"
                                            onClick={() => openReply(item)}
                                            aria-label={`Balas ucapan dari ${item.name}`}
                                        >
                                            <MessageSquareText className="size-4" />
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            className="text-red-500 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950"
                                            onClick={() => setToDelete(item)}
                                            aria-label={`Hapus ucapan dari ${item.name}`}
                                        >
                                            <Trash2 className="size-4" />
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </CardContent>
                </Card>
            </div>
            <Dialog open={toDelete !== null} onOpenChange={(open) => !open && setToDelete(null)}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Hapus ucapan ini?</DialogTitle>
                        <DialogDescription>
                            Ucapan dari{' '}
                            <span className="font-medium">{toDelete?.name}</span> akan
                            dihapus permanen dari buku tamu. Tindakan ini tidak dapat
                            dibatalkan.
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <Button variant="outline" onClick={() => setToDelete(null)}>
                            Batal
                        </Button>
                        <Button variant="destructive" onClick={handleDelete}>
                            <Trash2 className="size-4" />
                            Hapus
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            <Dialog
                open={toReply !== null}
                onOpenChange={(open) => {
                    if (!open) {
                        setToReply(null);
                    }
                }}
            >
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Balas ucapan</DialogTitle>
                        <DialogDescription>
                            Menanggapi ucapan dari{' '}
                            <span className="font-medium">{toReply?.name}</span>.
                            {toReply?.reply && (
                                <span className="text-muted-foreground mt-1 block text-xs">
                                    Menimpa balasan yang sudah ada.
                                </span>
                            )}
                        </DialogDescription>
                    </DialogHeader>
                    <div className="my-2 rounded-md bg-neutral-100 p-3 text-sm dark:bg-neutral-900">
                        <p className="whitespace-pre-wrap">{toReply?.message}</p>
                    </div>
                    <textarea
                        className="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex min-h-[100px] w-full rounded-md border px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="Tulis balasan untuk tamu…"
                        value={replyText}
                        onChange={(e) => setReplyText(e.target.value)}
                        rows={4}
                    />
                    <DialogFooter>
                        <Button variant="outline" onClick={() => setToReply(null)}>
                            Batal
                        </Button>
                        <Button
                            onClick={handleReply}
                            disabled={replyText.trim().length === 0}
                        >
                            <MessageSquareText className="size-4" />
                            Simpan Balasan
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </>
    );
}

AdminGuestbook.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: dashboard(),
        },
        {
            title: 'Moderasi Ucapan',
            href: '/admin/guestbook',
        },
    ],
};
