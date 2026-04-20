<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $data['user'] = User::inRandomOrder()->first();
        return view('admin.wheel.index',$data);
    }
}
