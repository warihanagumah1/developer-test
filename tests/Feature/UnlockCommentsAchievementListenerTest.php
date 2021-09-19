<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\User;
use App\Listeners\UnlockCommentAchievement;
use App\Events\CommentWritten;

class UnlockCommentsAchievementListenerTest extends TestCase
{

    
    /**
     * A basic feature test example.
     * @test
     */
    public function unlockCommentAchievement()
    {
        $user = User::factory()->create();

        $comment = Comment::factory()->create([
                'body' => 'my first comment',
                'user_id' => $user->id,
            ]);
    
        $listener = new UnlockCommentAchievement();

        $listener->handle(new CommentWritten($comment));

        $this->assertSame("my first comment", $comment->body);

    }
}
