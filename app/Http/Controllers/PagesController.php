<?php

namespace Dayscore\Http\Controllers;

use Illuminate\Http\Request;
use Dayscore\Http\Requests;
use Dayscore\Http\Controllers\Controller;

class PagesController extends Controller
{
    public function dashboard()
    {
        return view('pages.dashboard');
    }

    public function help()
    {
        return view('pages.help');
    }
}
