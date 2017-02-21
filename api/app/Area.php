<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Donor;

class Area extends Model
{
    public function donors()
    {
    	return $this->hasMany('App\Donor');
    }

    public function parentArea()
    {
    	return $this->belongsTo('App\Area','parent');
    }
}
