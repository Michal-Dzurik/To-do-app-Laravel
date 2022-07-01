<?php

namespace App\Observers;

use App\Mail\ShareMail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Mail\Message;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\AbstractPart;

class TaskObserver
{

    /**
     * Handle the User "shared" task.
     *
     * @param  Task $task
     *
     * @return void
     */
    public function shared($task)
    {

        $data = $task->getEventData('shared');
        $user = $data[0];
        $action_user = $data[1];

        $body = '<p>Hi,<br>' . $action_user->name . ' shared this task with you: <br> <br>Title: ' . $task->title . '<br>Description: ' . $task->description . '<br>Category: ' . $task->category . ' </p>';
        \Mail::html($body, function (Message $message) use ($task, $user) {
            $message
                ->to($user->email)
                ->from('todolaravel@gmail.com')
                ->subject("Task was shared with you");
        });
    }

    /**
     * Handle the User "shared" task.
     *
     * @param  Task $task
     *
     * @return void
     */
    public function unshared($task)
    {

        $data = $task->getEventData('unshared');
        $user = $data[0];
        $action_user = $data[1];

        $body = '<p>Hi,<br>' . $action_user->name . ' unshared this task with you: <br> <br>Title: ' . $task->title . '<br>Description: ' . $task->description . '<br>Category: ' . $task->category . ' </p>';
        \Mail::html($body, function (Message $message) use ($task, $user) {
            $message
                ->to($user->email)
                ->from('todolaravel@gmail.com')
                ->subject("Task was unshared with you");
        });
    }

    /**
     * Handle the User "done" task.
     *
     * @param  Task $task
     *
     * @return void
     */
    public function done($task)
    {

        $data = $task->getEventData('done');
        $user = $data[0];

        $body = '<p>Hi,<br>' . $user->name . ' You have completed the task: <br> <br>Title: ' . $task->title . '<br>Description: ' . $task->description . '<br>Category: ' . $task->category . ' </p>';
        \Mail::html($body, function (Message $message) use ($task, $user) {
            $message
                ->to($user->email)
                ->from('todolaravel@gmail.com')
                ->subject("You have completed the task");
        });
    }

    /**
     * Handle the User "undone" task.
     *
     * @param  Task $task
     *
     * @return void
     */
    public function undone($task)
    {

        $data = $task->getEventData('undone');
        $user = $data[0];

        $body = '<p>Hi,<br>' . $user->name . ' You have set the task as undone: <br> <br>Title: ' . $task->title . '<br>Description: ' . $task->description . '<br>Category: ' . $task->category . ' </p>';
        \Mail::html($body, function (Message $message) use ($task, $user) {
            $message
                ->to($user->email)
                ->from('todolaravel@gmail.com')
                ->subject("You have undone the task");
        });
    }




}
