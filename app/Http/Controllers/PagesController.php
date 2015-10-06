<?php

namespace Dayscore\Http\Controllers;

use Dayscore\Optafeed;
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
        $optafeeds = Optafeed::all();
        return view('pages.dashboard',compact('optafeeds'));
    }

    public function help()
    {
        return view('pages.help');
    }
}
