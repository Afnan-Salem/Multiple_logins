<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Show role selection dashboard.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $id= Auth::user()->id;
        $user = new User();
        $roles= $user->get_userRoles($id);
        return view('home',['roles'=>$roles]);
    }

    /**
     * Redirects to home page after login
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function redirectHome()
    {
        if(session()->has('curr_role')) {
            $curr_role=session()->get('curr_role');
            return view('role', ['role' => $curr_role]);
        }
        else{
            return $this->index();
        }
    }

    /**
     *activate user account
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verify()
    {
        $user=new User();
        $user->activateAccount();
        return $this->index();
    }

}
