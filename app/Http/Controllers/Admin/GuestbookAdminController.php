<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestbookMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GuestbookAdminController extends Controller
{
    /**
     * Moderation dashboard for guestbook messages.
     */
    public function index(): Response
    {
        $messages = GuestbookMessage::query()
            ->latest()
            ->limit(500)
            ->get()
            ->map(fn (GuestbookMessage $message) => [
                'id' => $message->id,
                'name' => $message->name,
                'message' => $message->message,
                'attendance' => $message->attendance,
                'reply' => $message->reply,
                'replied_at' => $message->replied_at?->locale('id')->diffForHumans(),
                'time' => $message->created_at?->locale('id')->diffForHumans(),
                'created_at' => $message->created_at?->locale('id')->isoFormat('D MMMM Y HH:mm'),
            ]);

        return Inertia::render('admin/guestbook', [
            'messages' => $messages,
            'stats' => [
                'total' => GuestbookMessage::count(),
                'hadir' => GuestbookMessage::where('attendance', 'Hadir')->count(),
                'tidak_hadir' => GuestbookMessage::where('attendance', 'Tidak hadir')->count(),
            ],
        ]);
    }

    /**
     * Delete a guestbook message.
     */
    public function destroy(Request $request, GuestbookMessage $guestbookMessage): RedirectResponse
    {
        $guestbookMessage->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "Ucapan dari \"{$guestbookMessage->name}\" telah dihapus.",
        ]);

        return back();
    }

    /**
     * Reply to a guestbook message.
     */
    public function reply(Request $request, GuestbookMessage $guestbookMessage): RedirectResponse
    {
        $validated = $request->validate([
            'reply' => ['required', 'string', 'min:1', 'max:2000'],
        ]);

        $guestbookMessage->update([
            'reply' => $validated['reply'],
            'replied_at' => now(),
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "Balasan untuk \"{$guestbookMessage->name}\" telah disimpan.",
        ]);

        return back();
    }
}
