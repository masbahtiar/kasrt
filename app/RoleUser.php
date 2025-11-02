<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
	protected $primaryKey = 'user_id';
    protected $table = 'role_users';
	public $timestamps = false;
	protected $fillable = [
		'user_id', 'role_id'];

    	/*
    	* Method untuk yang mendefinisikan relasi antara model user dan model userRole
    	*/ 	
    	public function users()
    	{
    		return $this->belongsToMany(User::class);
    	}

    	/*
    	* Method untuk mengambil object dari user
    	*/ 
    	public function getUserObject()
    	{
    		return $this->belongsTo(User::class);
    	}

    	/*
    	* Method untuk mengambil object dari role
    	*/ 
    	public function getRoleObject()
    	{
        	return $this->belongsTo(Role::class);
    	}
}
