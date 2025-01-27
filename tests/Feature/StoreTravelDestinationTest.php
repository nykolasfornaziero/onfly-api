<?php

namespace Tests\Feature;

use App\Models\TravelDestination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class StoreTravelDestinationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_travel_destination_as_authenticated_user()
    {
        Artisan::call('db:seed', ['--class' => 'UserSeeder']);

        $data = [
            'destination' => 'Paris',
            'departure_date' => now()->addDays(1)->toDateString(),
            'return_date' => now()->addDays(5)->toDateString(),
        ];

        $user = \App\Models\User::factory()->create();
        $user->assignRole('admin');
        $token = JWTAuth::fromUser($user);

        $response = $this->post(route('travel-destinations.store'), [
            'destination' => 'Paris',
            'departure_date' => '2025-01-30',
            'return_date' => '2025-02-10',
        ], [
            'Authorization' => 'Bearer ' . $token,  // Passando o token no cabeçalho Authorization
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['id','destination','departure_date','return_date','status','is_trip_active','user' => ['id','name']]);

    }
}
