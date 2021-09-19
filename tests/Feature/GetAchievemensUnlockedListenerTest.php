<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\User;
use App\Listeners\GetAchievemnetsUnlocked;
use App\Events\AchievementUnlocked;

class GetAchievemensUnlockedListenerTest extends TestCase
{
    /**
     * A basic feature test example.
     *@test
     */
    public function getUnlockedAchievement()
    {
        $user = User::factory()->create();
        $name = "First Comment Written";


        $comment = Comment::factory()->create([
            'body' => 'my first comment',
            'user_id' => $user->id,
        ]);
    
        $listener = new GetAchievemnetsUnlocked();

        $listener->handle(new AchievementUnlocked($name, $user));

        $this->assertSame("my first comment", $comment->body);
    }
}
