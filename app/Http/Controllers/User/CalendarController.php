<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\Cloud;
use App\Models\Notes;
use App\Models\ChatMessage;
use Auth;
use DB;

class CalendarController extends Controller
{
     protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = User::where('id', Auth::id())->first();

            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index()
    {
        return view('user.calendar.index');
    }
}