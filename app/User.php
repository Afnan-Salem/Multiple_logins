<?php

namespace App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Controllers\HomeController;
use Carbon;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname','surname', 'email', 'password','gender'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get users having the orchestra officer role
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orchestra_officer()
    {
        return $this->hasOne('App\Orchestra');
    }

    /**
     * define role_user relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    /**
     * Add user
     * called upon member registration request
     * @param $data, form inputs
     * @return mixed
     */
    public function create_member($data)
    {
        $new =Carbon\Carbon::today()->format('Y-m-d');
        return DB::insert('INSERT INTO users(`fname`, `surname`, `email`, `password`, `gender`,
       `activated`, `created_at`, `updated_at`) values(?,?,?,?,?,?,?,?)',
            [$data->fname,$data->surname,$data->email,bcrypt($data->password),$data->gender,
                '0',$new,$new]);
    }
    /**
     * assign roles to users
     * @param $role
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function assign($user_id,$role_id)
    {
        return DB::insert('insert into role_user (user_id,role_id) values(?,?)',[$user_id,$role_id]);
    }

    /**
     * @param $id
     * @return roles associated with the current authenticated user
     */
    public function get_userRoles($id)
    {
        $user_roles= DB::select('select `name` from roles,role_user WHERE user_id='.$id.' and roles.id=role_user.role_id');
        return $user_roles;
    }

    /**
     * set activated field to 1 'true'
     */
    public function activateAccount()
    {
        $id=Auth::user()->id;
         DB::update('update users set activated = ? where id = ?',[1,$id]);
    }

    /**
     * verify that user of the passed email exists
     * @param $email
     * @return user object
     */
    public function verify_mail($email)
    {
        return User::where('email', '=', $email)->first();
    }


}
