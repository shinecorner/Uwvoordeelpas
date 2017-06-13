<?php

namespace App;

use Alert;
use Redirect;
use App\Models\UserBan;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use DB;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];

    public static function getRoleErrorPopup() 
    {
        alert()->error('', 'U heeft niet de bevoegde rechten om deze pagina te bezoeken')->persistent('Sluiten');
    }

    public function companies()
    {
        return $this->hasMany('App\Models\Company', 'user_id');
    }

    public function companiesWaiter()
    {
        return $this->hasMany('App\Models\Company', 'waiter_user_id');
    }

    public static function banned($userId)
    {
        $banned = UserBan::where('user_id', $userId)
            ->where('expired_date', '>=', date('Y-m-d'))
            ->get()
            ->toArray()
        ;

        return $banned;
    }

}
