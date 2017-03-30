<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

//use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable {//implements CanResetPassword {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // -------------------------------------------------------------------------

    function updateEmail($email) {
        
        $user = Auth::user();
        
        $user->email = $email;
        
        if (!$user->save()) {
            return false;
        }
        
        return true;
    }

    // -------------------------------------------------------------------------

    function updateName($name) {
        
        $user = Auth::user();
        
        $user->name = $name;
        
        if (!$user->save()) {
            return false;
        }
        
        return true;
    }

    // -------------------------------------------------------------------------

    public function projects() {
        
        return $this->hasMany('App\Project', 'created_by');
    }

    public function getAvatar() {
        
        $options = '?d=mm&s=40';
        
        $source = urlencode('');
        
        return 'https://www.gravatar.com/avatar/' . md5($this->email) . $options;
    }


}
