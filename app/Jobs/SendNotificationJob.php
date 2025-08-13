<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\CustomNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $users;
    public $notification;

    public function __construct($users, $notification)
    {
        $this->users = $users;
        $this->notification = $notification;
    }

    public function handle()
    {
        foreach ($this->users as $user) {
            $user->notify($this->notification);
        }
    }
}