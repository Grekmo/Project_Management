<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectCompletedNotification extends Notification
{
    use Queueable;

    protected Project $project;
    /**
     * Create a new notification instance.
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Project Completed',
            'message' => 'The Project : ' . $this->project->name . ' has been marked as Completed by the Manager ' . $this->project->manager->name ,
            'project_id' => $this->project->id,
        ];
    }
}
