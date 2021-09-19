<?php

namespace App\Listeners;

use App\Events\AchivementUnlocked;
use App\Events\LessonWatched;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\AchievementUnlocked;
use Illuminate\Support\Facades\DB;

class UnlockLessonAchievement
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LessonWatched  $event
     * @return void
     */
    public function handle(LessonWatched $event)
    {
        //get and save user lesson watched
        $lesson = $event->lesson;
        $lesson_id = $lesson->id;
        $user = $event->user;
        $user_id = $user->id;

        DB::table('lesson_user')->insert(
            [
                'user_id' => $user_id,
                'lesson_id' => $lesson_id,
                'watched' => '1'
            ]
        );

        //get and count lessons user has watched
        $lessons_watched = DB::table('lesson_user')
            ->where('user_id', '=', $user_id)
            ->count();

        $achievement_name = '';

        if($lessons_watched >= 1 && $lessons_watched < 5) 
        {
            $achievement_name = "First Lesson Watched";
            
        }else if($lessons_watched >= 5 && $lessons_watched < 10)
        {
            $achievement_name = "5 Lessons Watched";

        }else if($lessons_watched >= 10 && $lessons_watched < 25)
        {
            $achievement_name = "10 Lessons Watched";

        }else if($lessons_watched >= 25 && $lessons_watched < 50)
        {
            $achievement_name = "25 Lessons Watched";

        }else if($lessons_watched >= 50)
        {
            $achievement_name = "50 Lessons Watched";

        }

        //fire AchievementUnlocked event
        event(new AchievementUnlocked($achievement_name, $user));

    }
}
