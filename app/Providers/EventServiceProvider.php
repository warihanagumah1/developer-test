<?php

namespace App\Providers;

use App\Events\LessonWatched;
use App\Events\CommentWritten;
use App\Events\AchievementUnlocked;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CommentWritten::class => [
            UnlockCommentAchievement::class,
        ],
        LessonWatched::class => [
            UnlockLessonAchievement::class,
        ],
        AchievementUnlocked::class => [
            //this listener listens for and count number of achivements to unloack a badge
            GetAchievemnetsUnlocked::class,

        ],
        BadgeUnlocked::class => [
            //
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
