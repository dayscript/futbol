<?php

namespace Dayscore\Http\Controllers;

use Dayscore\Optafeed;
use Dayscore\User;
use Illuminate\Http\Request;
use Dayscore\Http\Requests;
use Dayscore\Http\Controllers\Controller;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware( 'auth' );
    }

    public function dashboard()
    {
        $users = User::all();
        $optafeeds = Optafeed::latest();
        return view('pages.dashboard',compact('optafeeds','users'));
    }

    public function help()
    {
        return view('pages.help');
    }
}
