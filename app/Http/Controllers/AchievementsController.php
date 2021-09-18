<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        
        $achievements_unlocked = $this->getUnlocked_Achivements($user->id);
        return response()->json([
            'unlocked_achievements' => $achievements_unlocked['all'],
            'next_available_achievements' => $this->getNextAvailableAchievements($user->id),
            'current_badge' => $this->getCurrentBadge($user->id),
            'next_badge' => $this->getNextBadge($user->id),
            'remaing_to_unlock_next_badge' => $this->getRemainingAchievements($user->id)
        ]);
    }

    private function getUnlocked_Achivements($user_id){
        //get user achievment for comments
        $num_comments = Comment::where('user_id', $user_id)->count();

        //push achivements to array
        $comments_achievments = array();
        if($num_comments == 1)
        {
            array_push($comments_achievments, "First Comment Written");
        }
        if($num_comments > 1 && $num_comments < 5)
        {
            array_push($comments_achievments, "3 Comments Written");

        }
        if($num_comments >= 5 && $num_comments < 10)
        {
            array_push($comments_achievments, "5 Comments Written");

        }
        if($num_comments >= 10 && $num_comments < 20)
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

        if($lessons_watched == 1)
        {
            array_push($lessons_achievements, "First Lesson Watched");
        }
        if($lessons_watched >= 5 && $lessons_watched < 10)
        {
            array_push($lessons_achievements, "5 Lessons Watched");
    
        }
        if($lessons_watched >= 10 && $lessons_watched < 25)
        {
            array_push($lessons_achievements, "10 Lessons Watched");
    
        }
        if($lessons_watched >= 25 && $lessons_watched < 50)
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

    private function getNextAvailableAchievements($user_id){

        $achievements_unlocked = $this->getUnlocked_Achivements($user->id);
        $comments = $achievements_unlocked['comments'];
        $lessons = $achievements_unlocked['lessons'];

        //get lass comments achievmengts
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
        }else {
            array_push($next_achievements, $recent_comment_achievement); 
        }


        //get last lesson achievmengts
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
        }else {
            array_push($next_achievements, $recent_lesson_achievement); 
        }

        return $next_achievements;

    }

    private function getCurrentBadge($user_id){

        $achievements_unlocked = $this->getUnlocked_Achivements($user->id);
        $count_all = count($achievements_unlocked['all']);

        $badge_name = '';
        if($count_all == 0)
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

    private function getNextBadge($user_id){

        $current_badge = $this->getCurrentBadge($user->id);
        

        $next_badge = "";

        if($current_badge == "Beginner")
        {
            $next_badge = "Intermediate";

        }else if($current_badge == "Intermediate")
        {
            $next_badge = "Advanced";
    
        }else if($current_badge == "Advanced"){

            $next_badge = "Master";
        }else
        {
            $next_badge = $current_badge;

        }

        return $next_badge;
    }

    private function getRemainingAchievements($user_id){

        $achievements_unlocked = $this->getUnlocked_Achivements($user->id);
        $count_all = count($achievements_unlocked['all']);

        $num_next_achievements = 0;
        if($count_all == 0)
        {
            $num_next_achievements = 4;
        }else if($count_all >= 0 && $count_all < 4)
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
