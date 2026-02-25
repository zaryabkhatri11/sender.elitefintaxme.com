<?php

namespace App\Http\Controllers;

use App\Notifications\TestPushNotification;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function testPush()
    {
        if (!\Auth::check()) {
            return json_encode(["status" => false, "message" => "login required"]);
        }
        \Auth::user()->notify(new TestPushNotification());
    }
}
