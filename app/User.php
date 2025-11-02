<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'username', 'verified',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*
    * Method untuk yang mendefinisikan relasi antara model user dan model Role
    */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id');
    }

    /*
    * Method untuk menambahkan role (hak akses) baru pada user
    */
    public function putRole($role)
    {
        if (is_string($role)) {
            $role = Role::whereRoleName($role)->first();
        }
        return $this->roles()->attach($role);
    }

    /*
    * Method untuk menghapus role (hak akses) pada user
    */
    public function forgetRole($role)
    {
        if (is_string($role)) {
            $role = Role::whereRoleName($role)->first();
        }
        return $this->roles()->detach($role);
    }

    /*
    * Method untuk mengecek apakah user yang sedang login punya hak akses untuk mengakses page sesuai rolenya
    */
    public function hasRole($roleName)
    {
        foreach ($this->roles as $role) {
            if ($role->role_name === $roleName) return true;
        }
        return false;
    }
    public function sekolahs()
    {
        return $this->belongsToMany(Sekolah::class, 'sekolah_users', 'user_id');
    }

    /*
    * Method untuk menambahkan role (hak akses) baru pada user
    */
    public function putSekolah($id)
    {
        if (is_integer($id)) {
            $id = Sekolah::find($id);
        }
        return $this->sekolahs()->attach($id);
    }

    /*
    * Method untuk menghapus role (hak akses) pada user
    */
    public function forgetSekolah($id)
    {
        if (is_integer($id)) {
            $id = Sekolah::find($id);
        }
        return $this->sekolahs()->detach($id);
    }

    /*
    * Method untuk mengecek apakah user yang sedang login punya hak akses untuk mengakses page sesuai rolenya
    */
    public function hasSekolah($id)
    {
        foreach ($this->sekolahs as $sekolah) {
            if ($sekolah->id === is_integer($id)) return true;
        }
        return false;
    }
    public function verifyUser()
    {
        return $this->hasOne('App\VerifyUser');
    }
}
