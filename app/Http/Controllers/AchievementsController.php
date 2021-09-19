<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        
        //call private method to get all, comment and lesson achivements 
        $achievements_unlocked = $this->getUnlockedAchivements($user->id);

        return response()->json([
            'unlocked_achievements' => $achievements_unlocked['all'],
            'next_available_achievements' => $this->getNextAvailableAchievements($user->id),
            'current_badge' => $this->getCurrentBadge($user->id),
            'next_badge' => $this->getNextBadge($user->id),
            'remaing_to_unlock_next_badge' => $this->getRemainingAchievements($user->id)
        ]);
    }

    /**
     * getUnlockedAchivements get all achivemnent for comments written and lessons watched and individual achivements
     * @param  mixed $user_id id of user of interest
     *
     * @return mixed array containing next achivement for both commnets and next for comments and lessons 
     */

    private function getUnlockedAchivements($user_id)
    {
        //get user achievment for comments
        $num_comments = Comment::where('user_id', $user_id)->count();

        //push achivements to array
        $comments_achievments = array();
        if($num_comments >= 1)
        {
            array_push($comments_achievments, "First Comment Written");
        }
        if($num_comments >= 3)
        {
            array_push($comments_achievments, "3 Comments Written");

        }
        if($num_comments >= 5)
        {
            array_push($comments_achievments, "5 Comments Written");

        }
        if($num_comments >= 10)
        {
            array_push($comments_achievments, "10 Comment Written");

        }
        if($num_comments >= 20)
        {
            array_push($comments_achievments, "20 Comment Written");

        }

        //get and count lessons user has watched
        $lessons_watched = DB::table('lesson_user')
            ->where('user_id', '=', $user_id)
            ->count();

        //push achivements to array
        $lessons_achievements = array();

        if($lessons_watched >= 1)
        {
            array_push($lessons_achievements, "First Lesson Watched");
        }
        if($lessons_watched >= 5)
        {
            array_push($lessons_achievements, "5 Lessons Watched");
    
        }
        if($lessons_watched >= 10)
        {
            array_push($lessons_achievements, "10 Lessons Watched");
    
        }
        if($lessons_watched >= 25)
        {
            array_push($lessons_achievements, "25 Lessons Watched");
    
        }
        if($lessons_watched >= 50)
        {
            array_push($lessons_achievements, "50 Lessons Watched");
    
        }

        $all_achivements = array_merge($comments_achievments, $lessons_achievements);

        return [
            'all' => $all_achivements,
            'comments' => $comments_achievments,
            'lessons' => $lessons_achievements
        ];

    }

    /**
     * getNextAvailableAchievements get next available achivements for lessons watched and comments written
     * @param  mixed $user_id id of the user of interest
     *
     * @return mixed array containg the next achievement for lesson watched and comment written
     */

    private function getNextAvailableAchievements($user_id)
    {

        //call the private getUnlockedAchivements and get comments written and lessons watched achievements
        $achievements_unlocked = $this->getUnlockedAchivements($user_id);
        $comments = $achievements_unlocked['comments'];
        $lessons = $achievements_unlocked['lessons'];

        //get last achievement for comments written
        $recent_comment_achievement = array_pop($comments);

        //push next achivements to array
        $next_achievements = array();

        if($recent_comment_achievement == null)
        {
            array_push($next_achievements, "First Comment Written");

        }else if($recent_comment_achievement == "First Comment Written")
        {
            array_push($next_achievements, "3 Comments Written");
    
        }else if($recent_comment_achievement == "3 Comments Written"){

            array_push($next_achievements, "5 Comments Written");

        }else if($recent_comment_achievement == "5 Comments Written")
        {
            array_push($next_achievements, "10 Comment Written"); 

        }else if($recent_comment_achievement == "10 Comment Written")
        {
            array_push($next_achievements, "20 Comment Written"); 

        }

        //get last achievement for lessons watched
        $recent_lesson_achievement = array_pop($lessons);

        if($recent_lesson_achievement == null)
        {
            array_push($next_achievements, "First Lesson Watched");

        }else if($recent_lesson_achievement == "First Lesson Watched")
        {
            array_push($next_achievements, "5 Lessons Watched");
    
        }else if($recent_lesson_achievement == "5 Lessons Watched"){

            array_push($next_achievements, "10 Lessons Watched");

        }else if($recent_lesson_achievement == "10 Lessons Watched")
        {
            array_push($next_achievements, "25 Lessons Watched"); 

        }else if($recent_lesson_achievement == "25 Lessons Watched")
        {
            array_push($next_achievements, "50 Lessons Watched"); 

        }

        return $next_achievements;

    }


    /**
     * getCurrentBadge get user current badge based on number of achievements
     * @param  mixed $user_id id of the user of interest
     *
     * @return mixed name of user badge
     */

    private function getCurrentBadge($user_id)
    {

        //call the private getUnlockedAchivements and get  and count all achivements
        $achievements_unlocked = $this->getUnlockedAchivements($user_id);
        $count_all = count($achievements_unlocked['all']);

        $badge_name = '';
        if($count_all >= 0 && $count_all < 4)
        {
            $badge_name = 'Beginner';

        }else if($count_all >= 4 && $count_all < 8)
        {
            $badge_name = "Intermediate";

        }else if($count_all >= 8 && $count_all < 10)
        {
            $badge_name = "Advanced";

        }else if($count_all >= 10)
        {
            $badge_name = "Master";
        }

        return $badge_name;


    }

    /**
     * getNextBadge get next badge to be won
     * @param  mixed $user_id id of the user of interest
     *
     * @return mixed name of the next badge
     */

    private function getNextBadge($user_id)
    {
        //call private function getCurrentBadge to get user current badge
        $current_badge = $this->getCurrentBadge($user_id);
        

        $next_badge = "";

        if($current_badge == "Beginner")
        {
            $next_badge = "Intermediate";

        }else if($current_badge == "Intermediate")
        {
            $next_badge = "Advanced";
    
        }else if($current_badge == "Advanced"){

            $next_badge = "Master";
        }

        return $next_badge;
    }

    /**
     * getRemainingAchievements get number of remaining achivements needed to unloack the next badge
     * @param  mixed $user_id id of the user of interest
     *
     * @return mixed number of achivements needed to unlock the next badge
     */
    private function getRemainingAchievements($user_id)
    {
        //call the private getUnlockedAchivements and get  and count all achivements
        $achievements_unlocked = $this->getUnlockedAchivements($user_id);
        $count_all = count($achievements_unlocked['all']);

        $num_next_achievements = 0;
        if($count_all >= 0 && $count_all < 4)
        {
            $num_next_achievements = 4 - $count_all;

        }else if($count_all >= 4 && $count_all < 8)
        {
            $num_next_achievements = 8 - $count_all;

        }else if($count_all >= 8 && $count_all < 10)
        {
            $num_next_achievements = 10 - $count_all;
        }

        return $num_next_achievements;

    }
}
