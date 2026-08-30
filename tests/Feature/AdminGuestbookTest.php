<?php

namespace Tests\Feature;

use App\Models\GuestbookMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminGuestbookTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_admin_page(): void
    {
        $this->get('/admin/guestbook')->assertRedirect('/login');
    }

    public function test_non_admin_users_cannot_access_admin_page(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/guestbook')
            ->assertForbidden();
    }

    public function test_admin_can_view_moderation_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        GuestbookMessage::create([
            'name' => 'Budi',
            'message' => 'Selamat menempuh hidup baru!',
            'attendance' => 'Hadir',
        ]);

        $response = $this->actingAs($admin)->get('/admin/guestbook');

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('admin/guestbook')
            ->where('stats.total', 1)
            ->where('messages.0.name', 'Budi')
        );
    }

    public function test_admin_can_delete_a_message(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $message = GuestbookMessage::create([
            'name' => 'Sari',
            'message' => 'Turut berbahagia!',
            'attendance' => null,
        ]);

        $this->actingAs($admin)
            ->delete("/admin/guestbook/{$message->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('guestbook_messages', ['id' => $message->id]);
    }

    public function test_non_admin_cannot_delete_a_message(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $message = GuestbookMessage::create([
            'name' => 'Sari',
            'message' => 'Turut berbahagia!',
            'attendance' => null,
        ]);

        $this->actingAs($user)
            ->delete("/admin/guestbook/{$message->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('guestbook_messages', ['id' => $message->id]);
    }
}
