<?php

namespace Tests\Feature;

use App\Models\GuestbookMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestbookTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_guestbook_messages(): void
    {
        GuestbookMessage::create([
            'name' => 'Tamu Undangan',
            'message' => 'Selamat menempuh hidup baru!',
            'attendance' => 'Hadir',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Tamu Undangan');
        $response->assertSee('Selamat menempuh hidup baru!');
    }

    public function test_guest_can_submit_greeting(): void
    {
        $response = $this->withSession([])
            ->postJson('/guestbook', [
                'name' => 'Budi',
                'message' => 'Selamat atas pernikahannya!',
                'attendance' => 'Hadir',
            ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(201);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonPath('counts.hadir', 1);

        $this->assertDatabaseHas('guestbook_messages', [
            'name' => 'Budi',
            'attendance' => 'Hadir',
        ]);
    }

    public function test_validation_rejects_invalid_payload(): void
    {
        $response = $this->withSession([])
            ->postJson('/guestbook', [
                'name' => '',
                'message' => 'x',
                'attendance' => 'Mungkin',
            ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'message', 'attendance']);

        $this->assertDatabaseCount('guestbook_messages', 0);
    }

    public function test_messages_endpoint_returns_json(): void
    {
        GuestbookMessage::create([
            'name' => 'Sari',
            'message' => 'Turut berbahagia!',
            'attendance' => null,
        ]);

        $response = $this->getJson('/guestbook/messages');

        $response->assertOk();
        $response->assertJsonPath('status', 'success');
        $response->assertJsonPath('counts.total', 1);
        $response->assertJsonFragment([
            'name' => 'Sari',
            'message' => 'Turut berbahagia!',
        ]);
    }
}
