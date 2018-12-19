<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConsoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect()->route('console.dashboard');
    }

    public function dashboard()
    {
        return view('console.console');
    }
    public function back()
    {
        return redirect()->back();
    }
}
