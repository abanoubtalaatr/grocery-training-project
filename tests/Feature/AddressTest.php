<?php

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('test-token')->plainTextToken;
    $this->headers = ['Authorization' => 'Bearer '.$this->token];
});

it('requires authentication for all endpoints', function () {
    $this->getJson('/api/addresses')->assertUnauthorized();
    $this->getJson('/api/addresses/1')->assertUnauthorized();
    $this->postJson('/api/addresses', [])->assertUnauthorized();
    $this->putJson('/api/addresses/1', [])->assertUnauthorized();
    $this->deleteJson('/api/addresses/1')->assertUnauthorized();
    $this->postJson('/api/addresses/1/set-default')->assertUnauthorized();
});

describe('test index point', function () {
    it('lists all addresses for the authenticated user', function () {
        $addresses = Address::factory()
            ->count(3)
            ->for($this->user)
            ->create();
    
        $response = $this->getJson('/api/addresses', $this->headers);
    
        $response->assertOk()
            ->assertJsonStructure([
                'status', 'message', 'data' => ['addresses', 'total_count'],
            ])
            ->assertJsonPath('data.total_count', 3);
    });
    
    it('sorts addresses with default first then by newest', function () {
        $old = Address::factory()->for($this->user)->create(['is_default' => false, 'created_at' => now()->subDays(2)]);
        $default = Address::factory()->for($this->user)->create(['is_default' => true, 'created_at' => now()->subDay()]);
        $new = Address::factory()->for($this->user)->create(['is_default' => false, 'created_at' => now()]);
    
        $response = $this->getJson('/api/addresses', $this->headers);
    
        $response->assertOk();
        $ids = collect($response->json('data.addresses'))->pluck('id')->toArray();
        expect($ids[0])->toBe($default->id)
            ->and($ids[1])->toBe($new->id)
            ->and($ids[2])->toBe($old->id);
    });
    
    it('returns empty list when user has no addresses', function () {
        $response = $this->getJson('/api/addresses', $this->headers);
    
        $response->assertOk()
            ->assertJsonPath('data.total_count', 0)
            ->assertJsonPath('data.addresses', []);
    });
});

describe('test show point', function () {
    it('shows a single address', function () {
        $address = Address::factory()->for($this->user)->create();
    
        $response = $this->getJson("/api/addresses/{$address->id}", $this->headers);
    
        $response->assertOk()
            ->assertJsonPath('data.id', $address->id);
    });
    
    it('returns 404 when showing non-existent address', function () {
        $response = $this->getJson('/api/addresses/99999', $this->headers);
    
        $response->assertNotFound();
    });
    
    it('cannot show another users address', function () {
        $otherUser = User::factory()->create();
        $address = Address::factory()->for($otherUser)->create();
    
        $response = $this->getJson("/api/addresses/{$address->id}", $this->headers);
    
        $response->assertNotFound();
    });
});

describe('test store point', function () {
    it('creates a new address', function () {
        $payload = [
            'label' => 'Home',
            'full_name' => 'John Doe',
            'phone' => '1001234567890',
            'country_code' => '+20',
            'street_address' => '123 Main Street',
            'building_number' => '42',
            'floor' => '3',
            'apartment' => '15',
            'city' => 'Cairo',
            'state' => 'Cairo Governorate',
            'postal_code' => '11511',
            'country' => 'Egypt',
            'is_default' => true,
            'latitude' => 30.0444,
            'longitude' => 31.2357,
        ];
    
        $response = $this->postJson('/api/addresses', $payload, $this->headers);
    
        $response->assertCreated()
            ->assertJsonPath('message', 'Address created successfully')
            ->assertJsonStructure(['status', 'message', 'data' => ['id']]);
    
        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->user->id,
            'phone' => '1001234567890',
            'is_default' => true,
        ]);
    });
    
    it('auto-sets is_default when it is the first address', function () {
        $payload = [
            'full_name' => 'John Doe',
            'phone' => '1001234567890',
            'street_address' => '123 Main Street',
            'city' => 'Cairo',
        ];
    
        $response = $this->postJson('/api/addresses', $payload, $this->headers);
    
        $response->assertCreated();
        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->user->id,
            'is_default' => true,
        ]);
    });
    
    it('strips country code prefix from phone on store', function () {
        $payload = [
            'full_name' => 'John Doe',
            'phone' => '+201001234567',
            'country_code' => '+20',
            'street_address' => '123 Main Street',
            'city' => 'Cairo',
        ];
    
        $response = $this->postJson('/api/addresses', $payload, $this->headers);
    
        $response->assertCreated();
        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->user->id,
            'phone' => '1001234567',
        ]);
    });
    
    it('validates required fields on store', function () {
        $response = $this->postJson('/api/addresses', [], $this->headers);
    
        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['full_name', 'phone', 'street_address', 'city']);
    });
    
    it('validates phone format on store', function () {
        $payload = [
            'full_name' => 'John Doe',
            'phone' => '123',
            'street_address' => '123 Main Street',
            'city' => 'Cairo',
        ];
    
        $response = $this->postJson('/api/addresses', $payload, $this->headers);
    
        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['phone']);
    });
});

describe('test update point', function () {
    it('updates an address', function () {
        $address = Address::factory()->for($this->user)->create();
    
        $response = $this->putJson("/api/addresses/{$address->id}", [
            'full_name' => 'Jane Doe',
            'city' => 'Alexandria',
        ], $this->headers);
    
        $response->assertOk()
            ->assertJsonPath('message', 'Address updated successfully');
    
        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'full_name' => 'Jane Doe',
            'city' => 'Alexandria',
        ]);
    });
    
    it('strips country code prefix from phone on update', function () {
        $address = Address::factory()->for($this->user)->create(['phone' => '1001234567', 'country_code' => '+20']);
    
        $this->putJson("/api/addresses/{$address->id}", [
            'full_name' => 'John Doe',
            'phone' => '+201234567890',
            'country_code' => '+20',
            'street_address' => '123 Main Street',
            'city' => 'Cairo',
        ], $this->headers);
    
        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'phone' => '1234567890',
        ]);
    });
    
    it('validates on update with sometimes rules', function () {
        $address = Address::factory()->for($this->user)->create();
    
        $response = $this->putJson("/api/addresses/{$address->id}", [
            'phone' => 'abc',
        ], $this->headers);
    
        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['phone']);
    });
    
    it('cannot update another users address', function () {
        $otherUser = User::factory()->create();
        $address = Address::factory()->for($otherUser)->create();
    
        $response = $this->putJson("/api/addresses/{$address->id}", [
            'full_name' => 'Hacker',
        ], $this->headers);
    
        $response->assertNotFound();
    });
});

describe('test destroy point', function () {
    it('deletes an address', function () {
        $address = Address::factory()->for($this->user)->create();
    
        $response = $this->deleteJson("/api/addresses/{$address->id}", [], $this->headers);
    
        $response->assertOk()
            ->assertJsonPath('message', 'Address deleted successfully');
    
        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    });
    
    it('reassigns default when deleted address was default', function () {
        $default = Address::factory()->for($this->user)->default()->create();
        $other = Address::factory()->for($this->user)->create();
    
        $this->deleteJson("/api/addresses/{$default->id}", [], $this->headers);
    
        $this->assertDatabaseHas('addresses', [
            'id' => $other->id,
            'is_default' => true,
        ]);
    });
    
    it('does not reassign default when non-default address is deleted', function () {
        $default = Address::factory()->for($this->user)->default()->create();
        $other = Address::factory()->for($this->user)->create();
    
        $this->deleteJson("/api/addresses/{$other->id}", [], $this->headers);
    
        $this->assertDatabaseHas('addresses', [
            'id' => $default->id,
            'is_default' => true,
        ]);
    });
    
    it('cannot delete another users address', function () {
        $otherUser = User::factory()->create();
        $address = Address::factory()->for($otherUser)->create();
    
        $response = $this->deleteJson("/api/addresses/{$address->id}", [], $this->headers);
    
        $response->assertNotFound();
    });
});

describe('test set-default point', function () {
    it('sets an address as default', function () {
        $addresses = Address::factory()->count(2)->for($this->user)->create();
        $target = $addresses->first();
    
        $response = $this->postJson("/api/addresses/{$target->id}/set-default", [], $this->headers);
    
        $response->assertOk()
            ->assertJsonPath('message', 'Default address updated successfully');
    
        $this->assertDatabaseHas('addresses', [
            'id' => $target->id,
            'is_default' => true,
        ]);
    
        $this->assertDatabaseHas('addresses', [
            'id' => $addresses->last()->id,
            'is_default' => false,
        ]);
    });
    
    it('returns already_default when address is already default', function () {
        $address = Address::factory()->for($this->user)->default()->create();
    
        $response = $this->postJson("/api/addresses/{$address->id}/set-default", [], $this->headers);
    
        $response->assertOk()
            ->assertJsonPath('data.already_default', true)
            ->assertJsonPath('message', 'This address is already your default.');
    });
    
    it('cannot set default on another users address', function () {
        $otherUser = User::factory()->create();
        $address = Address::factory()->for($otherUser)->create();
    
        $response = $this->postJson("/api/addresses/{$address->id}/set-default", [], $this->headers);
    
        $response->assertNotFound();
    });
});
