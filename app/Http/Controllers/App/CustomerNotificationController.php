<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class CustomerNotificationController extends Controller
{

    public function index()
    {
        return view('frontend.notification.index');
    }
    public function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }
}
