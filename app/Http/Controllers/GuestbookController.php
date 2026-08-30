<?php

namespace App\Http\Controllers;

use App\Models\GuestbookMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GuestbookController extends Controller
{
    public function page(): View
    {
        $messages = GuestbookMessage::query()
            ->latest()
            ->limit(100)
            ->get();

        return view('undangan', [
            'messages' => $messages,
            'counts' => $this->counts(),
        ]);
    }

    public function index(): JsonResponse
    {
        $messages = GuestbookMessage::query()
            ->latest()
            ->limit(100)
            ->get()
            ->map(fn (GuestbookMessage $message) => $this->present($message));

        return response()->json([
            'status' => 'success',
            'counts' => $this->counts(),
            'data' => $messages,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'min:2', 'max:1000'],
            'attendance' => ['nullable', 'string', Rule::in(['Hadir', 'Tidak hadir'])],
        ]);

        $guestbookMessage = GuestbookMessage::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Terima kasih! Doa & ucapan Anda sudah terkirim.',
            'data' => $this->present($guestbookMessage),
            'counts' => $this->counts(),
        ], 201);
    }

    private function counts(): array
    {
        return [
            'hadir' => GuestbookMessage::where('attendance', 'Hadir')->count(),
            'tidak_hadir' => GuestbookMessage::where('attendance', 'Tidak hadir')->count(),
            'total' => GuestbookMessage::count(),
        ];
    }

    private function present(GuestbookMessage $guestbookMessage): array
    {
        return [
            'id' => $guestbookMessage->id,
            'name' => $guestbookMessage->name,
            'message' => $guestbookMessage->message,
            'attendance' => $guestbookMessage->attendance,
            'reply' => $guestbookMessage->reply,
            'replied_at' => $guestbookMessage->replied_at?->locale('id')->diffForHumans(),
            'time' => $guestbookMessage->created_at?->locale('id')->diffForHumans(),
        ];
    }
}
