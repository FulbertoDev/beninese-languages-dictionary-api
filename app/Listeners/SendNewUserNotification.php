<?php

namespace App\Listeners;

use App\Events\NewUserCreated;
use App\Mail\NewUserCreated as MailNewUserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;


class SendNewUserNotification implements ShouldQueue
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
    public function handle(NewUserCreated $event): void
    {
        $user = $event->user;
        Mail::to($user)->send(new MailNewUserCreated($user));
    }
}
