<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Area;

class Donor extends Model
{
	protected $table = 'donors';
    // $fillable = [];
    
    public function area()
    {
    	return $this->belongsTo('App\Area');
    }
}
