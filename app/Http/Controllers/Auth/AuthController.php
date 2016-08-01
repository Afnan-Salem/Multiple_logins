<?php

namespace App\Http\Controllers\Auth;

use App\Orchestra;
use App\Role;
use App\User;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
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
        $newRole_name=$request->role;   //requested role
        $email=$request->email;
        $user  = new User();
        $role = new Role();
        $user = $user->verify_mail($request->email);
        $role_id = $role->search_role($newRole_name);
        /*
         * user already exist in the system.
         * get user's roles
         * assign new role if not found
         * else redirect to login page
         */
        if ($user!=null) {
            $user_id=$user->id;
            $user_roles=$user->get_userRoles($user_id);
            foreach($user_roles as $role) {
                if($role->name == $newRole_name) {
                    $request->session()->flash('alert-danger', 'User '.$email.' Is Already '.$newRole_name.' Login! ');
                    return redirect('/login');
                }
                else if($user->fname!=$request->fname || $user->surname!=$request->surname ||
                    $user->gender!=$request->gender || Hash::check($request->password, $user->password)==0) {
                    $request->session()->flash('alert-danger','Credentials do not match for user '.$request->email.'');
                    return redirect()->back();
                }
                else {
                    if($newRole_name == 'orchestra') {
                        $orchestra = new Orchestra();
                        $orchestra -> create_record($user_id,Input::get('orchestra'));
                    }
                    $user->assign($user_id,$role_id);
                    //manually login to make user authenticated when redirecting home
                    Auth::login($user, true);
                    return view('home',['roles'=>$user->get_userRoles($user_id)]);
                }
            }
        }
        // register new user
       else {
            $user=$this->create(Input::all());
            $user_id = $user->id;
            if($newRole_name == 'orchestra') {
                $orchestra = new Orchestra();
                $orchestra -> create_record($user_id,Input::get('orchestra'));
            }
            $user->assign($user_id,$role_id);
            $this->send_verification(Input::all());
            $request->session()->flash('alert-success', 'A Confirmation Mail Has Sent To Your Email, Please Verify!');
            return redirect('/login');
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
