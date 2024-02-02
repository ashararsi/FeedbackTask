<?php

namespace App\Listeners;

use App\Events\NewFeedback;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Notifications\Notification;
use App\Notifications\RealTimeNotification;

class NewFeedbackListeners
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewFeedback $event): void
    {
        $user = auth()->user();

        $user->notify(new RealTimeNotification($event->message));


    }
}
