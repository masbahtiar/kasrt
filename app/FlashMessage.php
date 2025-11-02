<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashMessage extends Model
{
    // protected $table = "flashmessages";
    protected $fillable = [
        'id', 'judul', 'isi_pesan', 'start_date', 'end_date', 'user_id', 'aktif'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, RoleFlashMessage::class);
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
}
