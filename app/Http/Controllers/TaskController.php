<?php

namespace App\Http\Controllers;

use App\Http\Requests\tasks\TaskCreateRequest;
use App\Http\Requests\tasks\TaskUpdateRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Helpers;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $task = Task::withoutTrashed()
            ->title($request->title)
            ->description($request->description)
            ->done($request->done)
            ->orderBy(Helpers::getOrderBy($request->orderby),Helpers::getOrderDirection($request->direction))
            ->paginate(Helpers::getPerPage($request->pepage));

        return response([
            'status' => 'success',
            'data' => $task
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(TaskCreateRequest $request)
    {
        $paramsNeeded = Config::get('tasks.needed_params');

        if ($request->safe()->only($paramsNeeded)){
            $task = Task::create($request->only($paramsNeeded));

            $task->users()->attach($request->user()->id);

            return response([
                'status' => 'success',
                'data' => $task
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'Validation error'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::withoutTrashed()->findOrFail($id);

        return response([
            'status' => 'success',
            'data' => $task
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaskUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(TaskUpdateRequest $request, $id)
    {
        $paramsNeeded = Config::get('tasks.needed_params');

        $task = Task::withoutTrashed()->findOrFail($id);

        if ($task == null) {
            return response([
                'status' => 'error',
                'message' => 'Task not found'
            ],Response::HTTP_NOT_FOUND);
        }

        $gate = Gate::inspect('update',$task);

        if ($gate->allowed()){
            if ($request->safe()->only($paramsNeeded)){

                $task->update($request->only($paramsNeeded));

                return response([
                    'status' => 'success',
                    'data' => $task
                ]);
            }
        }


        return response([
            'status' => 'error',
            'message' => 'Task is not yours, and it\'s not even shared with you'
        ],Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Set resource as deleted.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $task = Task::withoutTrashed()->findOrFail($id);
        $gate = Gate::inspect('delete',$task);

        if ($gate->allowed()){
            $task->delete();
            return response([
                'status' => 'success',
            ]);
        }


        return response([
            'status' => 'error',
            'message' => 'Task is not yours.'
        ],Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Set resource as undeleted.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function undestroy($id)
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        $gate = Gate::inspect('delete',$task);

        if ($gate->allowed()){
            $task->restore();

            return response([
                'status' => 'success',
            ]);
        }
        return response([
            'status' => 'error',
            'message' => 'Task is not yours.'
        ],Response::HTTP_UNAUTHORIZED);

    }

    /**
     * Set resource as done.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function done($id)
    {
        $task = Task::withoutTrashed()->findOrFail($id);
        $gate = Gate::inspect('update',$task);

        if ($gate->allowed()){
            $task->update(["done" => 1]);

            $task->makeDone(\Auth::user());

            return response([
                'status' => 'success',
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'Task is not yours, and it\'s not even shared with you'
        ],Response::HTTP_UNAUTHORIZED);

    }

    /**
     * Set resource as undone.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function undone($id)
    {
        $task = Task::withoutTrashed()->findOrFail($id);
        $gate = Gate::inspect('update',$task);

        if ($gate->allowed()){
            $task->update(["done" => 0]);

            $task->makeUndone(\Auth::user());

            return response([
                'status' => 'success',
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'Task is not yours, and it\'s not even shared with you'
        ],Response::HTTP_UNAUTHORIZED);

    }

    /**
     * Share resource with another user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function share($id,$user_id)
    {
        $task = Task::withoutTrashed()->findOrFail($id);
        $gate = Gate::inspect('update',$task);

        if ($gate->allowed()){
            try {
                $user = User::findOrFail($user_id);
                $task->users()->attach($user->id,['shared' => true]);

                // Fire up event so email can be sent
                $task->makeShared($user,\Auth::user());


                return response([
                    'status' => 'success',
                ]);
            }catch (\Exception $exception){
                return response([
                    'status' => 'error',
                    'message' => 'This task is already shared with this user',
                ]);
            }
        }

        return response([
            'status' => 'error',
            'message' => 'Task is not yours, and it\'s not even shared with you'
        ],Response::HTTP_UNAUTHORIZED);

    }

    /**
     * UnShare resource with another user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unshare($id,$user_id)
    {

        $task = Task::withoutTrashed()->findOrFail($id);

        $gate = Gate::inspect('update',$task);
        if ($gate->allowed()){

            $user = User::findOrFail($user_id);
            $task->users()->detach($user->id);

            // Fire up event so email can be sent
            $task->makeUnshared($user,\Auth::user());

            return response([
                'status' => 'success',
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'Task is not yours, and it\'s not even shared with you'
        ],Response::HTTP_UNAUTHORIZED);

    }

    /**
     * Shows user who have access to this task.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showUsers($id){
        return response(['status' => 'success', 'data' => Task::find($id)->users()->get() ]);
    }



}
