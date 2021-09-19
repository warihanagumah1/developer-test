<?php

namespace App\Listeners;

use App\Models\Comment;
use App\Events\BadgeUnlocked;
use App\Events\AchievementUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GetAchievemnetsUnlocked
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
     * @param  AchievementUnlocked  $event
     * @return void
     */
    public function handle(AchievementUnlocked $event)
    {
        $user = $event->user;
        $user_id = $user->id;

        //get user achievment for comments
        $num_comments = Comment::where('user_id', $user_id)->count();

        $num_comment_achievements = 0;

        if($num_comments >= 1 && $num_comments < 3)
        {
            $num_comment_achievements = 1;

        }else if($num_comments >= 3 && $num_comments < 5)
        {
            $num_comment_achievements = 2;

        }else if($num_comments >= 5 && $num_comments < 10)
        {
            $num_comment_achievements = 3;

        }else if($num_comments >= 10 && $num_comments < 20)
        {
            $num_comment_achievements = 4;

        }else if($num_comments >= 20)
        {
            $num_comment_achievements = 5;

        }


        //get and count lessons user has watched
        $lessons_watched = DB::table('lesson_user')
            ->where('user_id', '=', $user_id)
            ->count();


        $num_lessons_achievement = 0;

        if($lessons_watched >= 1 && $lessons_watched < 5)
        {
            $num_lessons_achievement = 1;

        }else if($lessons_watched >= 5 && $lessons_watched < 10)
        {
            $num_lessons_achievement = 2;
    
        }else if($lessons_watched >= 10 && $lessons_watched < 25)
        {
            $num_lessons_achievement = 3;
    
        }else if($lessons_watched >= 25 && $lessons_watched < 50)
        {
            $num_lessons_achievement = 4;
    
        }else if($lessons_watched >= 50)
        {
            $num_lessons_achievement = 5;
    
        }
            
        $total_achievments = $num_lessons_achievement + $num_comment_achievements;

        $badge_name = '';
        if($total_achievments >= 0 && $total_achievments < 4) 
        {
            $badge_name = 'Beginner';

        }else if($total_achievments >= 4 && $total_achievments < 8)
        {
            $badge_name = "Intermediate";

        }else if($total_achievments >= 8 && $total_achievments < 10)
        {
            $badge_name = "Advanced";
            
        }else if($total_achievments >= 10)
        {
            $badge_name = "Master";
        }

        //fire Badge unlocked event
        event(new BadgeUnlocked($badge_name, $user));

    }
}
