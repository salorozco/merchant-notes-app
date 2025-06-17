<?php

namespace Tests\Feature\MerchantNotes;

use App\Models\User;
use Tests\TestCase;
use App\Models\Note;
use App\Models\Merchant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MerchantNoteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_can_list_notes_for_a_merchant(): void
    {
        $user = User::factory()->create();

        $merchant = Merchant::factory()->create([
            'user_id' => $user->id,
        ]);

        Note::factory()->count(3)->create([
            'merchant_id' => $merchant->id,
        ]);

        $response = $this->getJson("/api/merchants/{$merchant->id}/notes");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * @return void
     */
    public function test_can_create_note_for_merchant(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $merchant = Merchant::factory()->create([
            'user_id' => $user->getKey(),
        ]);

        $response = $this->postJson("/api/merchants/{$merchant->getKey()}/notes", [
            'body' => 'Test note content',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('notes', [
            'merchant_id' => $merchant->getKey(),
            'body' => 'Test note content',
        ]);
    }

    /**
     * @return void
     */
    public function test_can_update_note_for_merchant(): void
    {
        $user = User::factory()->create();

        $merchant = Merchant::factory()->create([
            'user_id' => $user->id,
        ]);

        $note = Note::factory()->create([
            'merchant_id' => $merchant->id,
            'body' => 'Original body',
        ]);

        $response = $this->putJson("/api/merchants/{$merchant->id}/notes/{$note->id}", [
            'body' => 'Updated body content',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'body' => 'Updated body content',
        ]);
    }

    /**
     * @return void
     */
    public function test_can_delete_note_for_merchant(): void
    {
        $user = User::factory()->create();

        $merchant = Merchant::factory()->create([
            'user_id' => $user->id,
        ]);

        $note = Note::factory()->create([
            'merchant_id' => $merchant->id,
        ]);

        $response = $this->deleteJson("/api/merchants/{$merchant->id}/notes/{$note->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('notes', [
            'id' => $note->id,
        ]);
    }
}

