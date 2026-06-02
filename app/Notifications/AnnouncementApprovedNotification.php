<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AnnouncementApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public Announcement $announcement) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Message approuvé',
            'message' => sprintf('« %s » est maintenant visible pour les familles.', $this->announcement->title),
            'url' => route('announcements.show', $this->announcement),
        ];
    }
}
