<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
 	public $incrementing = false;
	protected $primaryKey = 'id'; // or null
    public $timestamps = false;
    protected $fillable = [
        'id', 'nilai','keterangan'
    ];
}
