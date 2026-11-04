<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedToNotification extends Notification
{
    use Queueable;

    protected Task $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
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
            'title' => 'Task Assigned',
            'message' => 'You have been assigned a new Task : ' . $this->task->name . ' in Project : ' . $this->task->project->name,
            'task_id' => $this->task->id,
        ];
    }
}
