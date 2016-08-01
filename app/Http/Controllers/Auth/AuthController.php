<?php

namespace App\Http\Controllers\Auth;

use App\Orchestra;
use App\Role;
use App\User;
use Symfony\Component\HttpFoundation\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function validateForm(Request $request)
    {
         $this->validate($request, [
            'fname' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|confirmed',
            'orchestra' => 'required|max:225',
            'gender' => 'required|in:Male,Female',
        ]);
        return $this->check($request);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'fname' => $data['fname'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'gender' => Input::get('gender'),
            'activated' => 0,
        ]);
    }

    /**
     * check user account for different roles
     * @param $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    protected function check($request)
    {
        $user  = new User();
        $role = new Role();
        $user = $user->verify_mail($request->email);
        $role_id = $role->search_role($request->role);
        /*
         * user already exist in the system.
         * assign new role
         * else register new user
         */
        if ($user!=null) {
            return $this->assign_newRole($request,$user,$role_id);
        }
       else {
            return $this->register_newUserRole($request,$role_id);
        }
    }

    /**
     * @param $request
     * @param $user
     * @param $role_id
     * get user roles , assign the new role if not existed
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    protected function assign_newRole($request,$user,$role_id)
    {
        $newRole_name = $request->role;
        $user_id = $user->id;
        $user_roles = $user->get_userRoles($user_id);
        $boolean = $this->match_fields($request,$user);   //'true'..form inputs matches user's stored fields
        foreach($user_roles as $role) {
            if ($role->name == $newRole_name) {
                $request->session()->flash('alert-danger', 'User ' . $request->email . ' Is Already ' . $newRole_name . ' Login! ');
                return redirect('/login');
            }
        }
        if($boolean=='false') {
            $request->session()->flash('alert-danger','Credentials do not match for user '.$request->email.'');
            return redirect()->back();
        }
        else {
            if($newRole_name == 'orchestra') {
                $this->create_officer($user_id);
            }
            $user->assign($user_id,$role_id);
            Auth::login($user, true);   //manually login to make user authenticated when redirecting home
            return view('home',['roles'=>$user->get_userRoles($user_id)]);
        }
    }

    /**
     * @param $request
     * @param $role_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function register_newUserRole($request,$role_id)
    {
        $user=$this->create(Input::all());
        $user_id = $user->id;
        if($request->role == 'orchestra') {
            $this->create_officer($user_id);
        }
        $user->assign($user_id,$role_id);
        $this->send_verification(Input::all());
        $request->session()->flash('alert-success', 'A Confirmation Mail Has Sent To Your Email, Please Verify!');
        return redirect('/login');
    }

    /**
     * @param $user_id
     */
    protected function create_officer($user_id)
    {
        $orchestra = new Orchestra();
        $orchestra -> create_record($user_id,Input::get('orchestra'));
    }

    /**
     * compare input fields with database attributes
     * @param $request
     * @param $user
     * @return string
     */
    protected function match_fields($request,$user)
    {
        if($user->fname!=$request->fname || $user->surname!=$request->surname ||
            $user->gender!=$request->gender || Hash::check($request->password, $user->password)==0) {
            return 'false';
        }
    }

    /**
     * send verification email upon signup.
     * @param $data
     * @return mixed
     */
    public function send_verification($data)
    {
        return Mail::send('confirm_Registration', $data, function($message) use ($data)
        {
            $message->from("apptest0100@gmail.com", "test app");
            $message->subject("Confirm Your Registration");
            $message->to($data['email']);
        });
    }

}
