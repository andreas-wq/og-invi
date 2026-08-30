import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { MessageSquareHeart, Trash2, UserX, Users } from 'lucide-react';
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
import { destroy } from '@/routes/admin/guestbook';
import { dashboard } from '@/routes';

type MessageItem = {
    id: number;
    name: string;
    message: string;
    attendance: string | null;
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

    const handleDelete = () => {
        if (!toDelete) {
            return;
        }

        router.delete(destroy(toDelete.id), {
            preserveScroll: true,
            onSuccess: () => setToDelete(null),
        });
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
                            Kelola ucapan yang masuk melalui buku tamu undangan.
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="flex flex-col gap-3">
                        {messages.length === 0 && (
                            <p className="text-muted-foreground py-8 text-center text-sm">
                                Belum ada ucapan yang masuk.
                            </p>
                        )}

                        {messages.map((item) => (
                            <div
                                key={item.id}
                                className="flex items-start gap-3 rounded-lg border p-4"
                            >
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
                                </div>
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
                        ))}
                    </CardContent>
                </Card>
            </div>

            <Dialog
                open={toDelete !== null}
                onOpenChange={(open) => !open && setToDelete(null)}
            >
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
