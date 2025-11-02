<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleFlashMessage extends Model
{
    protected $primaryKey = 'flash_message_id';
    // protected $table = 'role_flashmessages';
    public $timestamps = false;
    protected $fillable = [
        'flash_message_id', 'role_id'
    ];

    /*
    	* Method untuk yang mendefinisikan relasi antara model user dan model userRole
    	*/
    /*
    	* Method untuk mengambil object dari user
    	*/

    /*
    	* Method untuk mengambil object dari role
    	*/
    public function getRoleObject()
    {
        return $this->belongsTo(Role::class);
    }
}
