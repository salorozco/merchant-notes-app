<?php

namespace Tests\Feature\Merchant;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MerchantControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_index_returns_all_merchants(): void
    {
        $user = User::factory()->create();
        Merchant::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/merchants');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'name', 'user', 'created_at', 'updated_at'],
                ],
            ])
            ->assertJson(['status' => 'success']);
    }

    /**
     * @return void
     */
    public function test_show_returns_single_merchant(): void
    {
        $user = User::factory()->create();
        $merchant = Merchant::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/merchants/{$merchant->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => ['id', 'name', 'user', 'created_at', 'updated_at'],
            ])
            ->assertJsonFragment([
                'id' => $merchant->id,
                'name' => $merchant->name,
            ]);
    }

    /**
     * @return void
     */
    public function test_merchants_by_user_returns_user_with_merchants(): void
    {
        $user = User::factory()->create();
        Merchant::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/merchants/by-user/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'merchants' => [
                        '*' => ['id', 'name', 'created_at', 'updated_at'],
                    ]
                ]
            ])
            ->assertJsonFragment([
                'id' => $user->id,
                'name' => $user->name,
            ]);
    }
}
