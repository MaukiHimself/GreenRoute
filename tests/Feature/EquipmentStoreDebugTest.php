<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class EquipmentStoreDebugTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_contractor_can_store_equipment(): void
    {
        $user = User::factory()->create([
            'user_type' => 'contractor',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->post(route('contractor.equipment.store'), [
            'name' => 'HTTP Equipment Test',
            'description' => 'Debug equipment save',
            'price' => '250',
            'unit' => 'per month',
            'category' => 'waste_bins',
            'is_available' => '1',
        ]);

        $response->assertRedirect(route('contractor.equipment.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'HTTP Equipment Test',
            'contractor_id' => $user->id,
        ]);
    }
}
