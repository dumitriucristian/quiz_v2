<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
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

    public function roles()
    {
        return $this->belongsToMany(\App\Role::class)->withTimestamps();
    }

    /**
     * @param string|array $roles
     */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) || abort(401, 'This action is unauthorized.');
        }
        return $this->hasRole($roles) ||  abort(401, 'This action is unauthorized.');
    }

    /**
     * Check multiple roles
     * @param array $roles
     */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn(‘name’, $roles)->first();
    }

    /**
     * Check one role
     * @param string $role
     */
    public function hasRole($role)
    {
        return null !== $this->roles()->where(‘name’, $role)->first();
    }

    public function createAnonimUser()
    {
        $anonimUser = new User();
        $anonimUser->email = bcrypt(now()).'_'.rand(0,1000);
        $anonimUser->name ="Guest";
        $anonimUser->password = bcrypt('secret');
        $anonimUser->save();
        $anonimUser->roles()->attach( Role::where('name','guest')->get()->first()->id ) ;

        return $anonimUser;
    }

    public function isValidGuest($cookie)
    {
        return (DB::table($this->getTable())->where('email', $cookie)->count() == 0) ? false : true;
    }

    public function getUserIdByCookie($cookie)
    {

        return DB::table($this->getTable())->where('email', $cookie)->first()->id;
    }


}
