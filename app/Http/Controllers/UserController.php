<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Lists all users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return response(['status' => 'success', 'data' => User::paginate(15)]);
    }

    /**
     * Returns the user with id .
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        return response(['status' => 'success', 'data' => User::findOrFail($id)]);
    }

    /**
     * Shows tasks of user.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showTasks($id){
        return response(['status' => 'success', 'data' => User::find($id)->tasks()->get() ]);
    }
}
