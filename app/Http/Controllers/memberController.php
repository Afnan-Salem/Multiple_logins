<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthController;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Role;
use Illuminate\Support\Facades\Input;
class memberController extends Controller
{

    /**
     * controller method to create user , assign member role, send email
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function register_member(Request $request)
    {
        $user = new User();
        if($user->verify_mail($request->email)!=null) {
            $request->session()->flash('alert-danger',''.$request->email.' already in the system!');
            return redirect()->back();
        }
        else {
            $role = new Role();
            $role_id = $role->search_role('member');
            $user->create_member($request);
            $user = $user->verify_mail($request->email);
            $user->assign($user->id,$role_id);
            $auth=new AuthController();
            $auth->send_verification(Input::all());
            $request->session()->flash('alert-success','Member '.$request->email.' has added successfully!');
            return redirect()->back();

        }
    }

}
