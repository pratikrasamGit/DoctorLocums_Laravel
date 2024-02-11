<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {       
        $user = Auth::user();
        if (Auth::user() && $user->hasRole('Administrator') || $user->hasRole('Admin')) {
            return view('admin.dashboard', ['user' => $user]);
            exit;
        }
        if (Auth::user() && $user->hasRole('Nurse')) {
            return redirect(route('personal-detail', [$user->nurse->id]));
            exit;
        }
        return view('dashboard', ['user' => $user]);
    }
}
