<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AchievementsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Retrive achivements for a user
     * @test
     */
    public function retrieveAchievements()
    {
        $user = User::factory()->create();
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);
    }

    
}
