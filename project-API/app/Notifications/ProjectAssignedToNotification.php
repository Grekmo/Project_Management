<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectAssignedToNotification extends Notification
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
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Project Assigned',
            'message' => 'You have been assigned as a Manager to the project : ' . $this->project->name ,
            'project_id' => $this->project->id,
            //'url' => route('projects.show', $this->project->id)
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            /*'title' => 'Project Assigned',
            'message' => 'You have been assigned as a Manager to the project : ' . $this->project->name ,
            'project_id' => $this->project->id,
            'url' => route('projects.show', $this->project->id)*/
        ];
    }
}
