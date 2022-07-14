<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\DB;

class TaskPolicy
{
    use HandlesAuthorization;


    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return Response
     */
    public function update(User $user, Task $task)
    {

        $count = DB::table('task_user')
            ->where("user_id", $user->id)
            ->where("task_id", $task->id)->get()->count();

        return $count == 1 ? Response::allow()
            : Response::deny('Task is not yours, and it\'s not even shared with you');
    }

    /**
     * Determine if the given post can be deleted by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return Response
     */
    public function delete(User $user, Task $task)
    {

        $count = DB::table('task_user')
            ->where("user_id", $user->id)
            ->where("task_id", $task->id)
            ->where("shared", false)->get()->count();

        return $count == 1 ? Response::allow()
            : Response::deny('Task is not yours');
    }
}
