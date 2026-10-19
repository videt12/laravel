<?php

namespace Tests\Feature;

use App\Models\Auth\User;
use Tests\TestCase;

class CambodiaLocationTest extends TestCase
{
    public function test_guest_cannot_access_location_apis(): void
    {
        $response = $this->getJson(route('locations.cambodia.districts', ['province' => 'Phnom Penh']));
        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_fetch_districts_by_province(): void
    {
        /** @var User $user */
        $user = User::factory()->make();

        $response = $this->actingAs($user)->getJson(route('locations.cambodia.districts', [
            'province' => 'Phnom Penh',
        ]));

        $response->assertOk();
        $response->assertJsonStructure([
            '*' => ['code', 'nameKm', 'nameEn', 'label'],
        ]);
    }

    public function test_authenticated_user_can_fetch_communes_by_district(): void
    {
        /** @var User $user */
        $user = User::factory()->make();

        $response = $this->actingAs($user)->getJson(route('locations.cambodia.communes', [
            'province' => 'Phnom Penh',
            'district' => 'Chamkar Mon',
        ]));

        $response->assertOk();
        $response->assertJsonStructure([
            '*' => ['code', 'nameKm', 'nameEn', 'label'],
        ]);
    }

    public function test_authenticated_user_can_fetch_villages_by_commune(): void
    {
        /** @var User $user */
        $user = User::factory()->make();

        $response = $this->actingAs($user)->getJson(route('locations.cambodia.villages', [
            'commune' => '010201',
        ]));

        $response->assertOk();
        $response->assertJsonStructure([
            '*' => ['code', 'nameKm', 'nameEn', 'label'],
        ]);
    }
}
