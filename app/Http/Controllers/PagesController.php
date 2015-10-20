<?php

namespace Dayscore\Http\Controllers;

use Dayscore\Opta\Game;
use Dayscore\Optafeed;
use Dayscore\User;
use Illuminate\Http\Request;
use Dayscore\Http\Requests;
use Dayscore\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
        $nextmatch = Game::where('date','>',date("Y-m-d H:i",time()+60*60*6))->orderBy('date','asc')->first();
        return view('pages.dashboard',compact('optafeeds','users','nextmatch'));
    }

    public function help()
    {
        return view('pages.help');
    }
}
