<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Lesson;
use App\Models\User;
use App\Listeners\UnlockLessonAchievement;
use App\Events\LessonWatched;
use Illuminate\Support\Facades\DB;

class UnlockLessonsAchievementListenerTest extends TestCase
{
    /**
     * A basic feature test example.
     * @test
     */
    public function unlockLessonAchievement()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();


        $lesson_user = DB::table('lesson_user')->insert(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'watched' => '1'
            ]
        );
    
    
        $listener = new UnlockLessonAchievement();

        $listener->handle(new LessonWatched($lesson, $user));

        $this->assertTrue($lesson_user);

    }
}
