<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodoListController extends Controller
{
    //

    function getIndex()
    {
        return view('todolist');
    }
}
