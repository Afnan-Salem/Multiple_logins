<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Role;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
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
        $role = new Role();
        $role_id = $role->search_role('member');
        $user->create_member($request);
        $user = $user->verify_mail($request->email);
        $user->assign($user->id,$role_id);
        $this->send_verification(Input::all());
        $request->session()->flash('alert-success','Member '.$request->email.' has added successfully!');
        return redirect()->back();

    }

    /**
     * send verification email to member
     * @param $data
     * @return mixed
     */
    protected function send_verification($data)
    {
        return Mail::send('confirm_Registration', $data, function($message) use ($data)
        {
            $message->from("apptest0100@gmail.com", "test app");
            $message->subject("Confirm Your Registration");
            $message->to($data['email']);
        });
    }

}
