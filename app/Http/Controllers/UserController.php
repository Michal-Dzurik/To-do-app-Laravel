<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return response()->json(['status' => 'success', 'data' => User::paginate(15)]);
    }

    public function show($id){
        return response()->json(['status' => 'success', 'data' => User::findOrFail($id)]);
    }
}
