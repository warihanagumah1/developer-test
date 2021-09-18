<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\User;
use App\Events\CommentWritten;
use App\Events\AchivementUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UnlockCommentAchievement
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
     * @param  CommentWritten  $event
     * @return void
     */
    public function handle(CommentWritten $event)
    {
        //get and save comment
        $comment = $event->comment;
        $current_timestamp = Carbon::now()->toDateTimeString();
        $comment->setAttribute('created_at', $current_timestamp);

        $comment->save();

        //get user id
        $userid = $comment->user_id;

        //save

        //get and count user comments
        $num_comments = Comment::where('user_id', $userid)->count();

        $achievement_name = '';

        if($num_comments == 1)
        {
            $achievement_name = "First Comment Written";
        }else if($num_comments > 1 && $num_comments < 5)
        {
            $achievement_name = "3 Comments Written";

        }else if($num_comments >= 5 && $num_comments < 10)
        {
            $achievement_name = "5 Comments Written";

        }else if($num_comments >= 10 && $num_comments < 20)
        {
            $achievement_name = "10 Comments Written";

        }else if($num_comments >= 20)
        {
            $achievement_name = "20 Comments Written";

        }

        //get user
        $user = User::find($userid);

        //fire AchievementUnlocked event
        event(new AchievementUnlocked($achievement_name, $user));
    }
}
