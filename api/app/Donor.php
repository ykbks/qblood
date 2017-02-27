<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Area;
use App\Donation;
use Carbon\Carbon;

class Donor extends Model
{

    protected $table = 'donors';
    protected $fillable = [
        'name',
        'gender',
        'religion',
        'dob', 
        'reg_type',
        'blood_group',
        'reg_no',
        'reg_batch',
        'reg_batch_suffix',
        'reg_complete',
        'phone_primary',
        'phone_secondary',
        'phone_request',
        'can_donate',
        'available',
        'unavailable_till', 
        'area_id',
        'address',
        'last_contacted_at', 
        'added_by',
        'updated_by'
    ];

    static function getGroups()
    {
        return ['A+', 'A-', 'AB+', 'AB-', 'B+', 'B-', 'O+', 'O-'];
    }

    static function getReligions()
    {
        return ['islam','hindu','buddhist','christian','others'];
    }

    static function getTypes()
    {
        return ['QA','QG','QPM'];
    }
	
    
    public function area()
    {
    	return $this->belongsTo('App\Area');
    }

    public function donations()
    {
    	return $this->hasMany('App\Donation')->orderBy('donations.date','DESC');
    }

    public function lastDonation()
    {
    	return $this->hasOne('App\Donation')->orderBy('donations.date','DESC');
    }

    public function totalDonations()
    {
        return $this->donations->count();
    }

    static function getAvailableCount($group = null) 
    {
        if(is_null($group) || is_array($group))
        {
            $data = [];
            $groups = is_null($group) ? self::getGroups() : $group;
            foreach ($groups as $g) {
                $arr['group'] = $g;
                $q = self::leftJoin('donations', 'donors.id', '=', 'donations.donor_id');
                $q->where(function($qu){
                    $qu->where('donations.date', '<=', Carbon::now()->subMonth(4)); // last donation at least 4 months ago
                    $qu->orWhereNull('donations.date');
                });
                $q->where('donors.blood_group',$g);
                $q->where('donors.can_donate',1);
                $q->where('donors.available',1);
                $arr['count'] = $q->distinct()->count();

                $data[] = $arr;
            }
            return $data;
        }
        else
        {
            // get one
            return self::where('blood_group',$group)->where('available','true')->where('can_donate',true)->count();
        }
    }


    static function getCompleteReg($param = null)
    {
        if(!is_null($param))
        {
            // if the param is an array, we are being asked to send 
            // a prepared reg from the given inputs (used by the Donor -> Store function)
            if(is_array($param))
            {
                $reg = '';
                $regType = strtoupper($param['reg_type']);
                $gender = strtolower($param['gender']);
                $regNo = $param['reg_no'];
                $regBatch = $param['reg_batch'];
                $regBatchSuffix = $param['reg_batch_suffix'];

                switch ($regType) {
                    case 'QG':
                      if ($gender === 'male') {
                        // do nothing
                      } else if ($gender === 'female') {
                        $reg .= 'F-'; // we add F- for female Graduates
                      }
                      break;

                    case 'QPM':
                      $reg .= 'M-'; // Add M- before the reg
                      break;

                    default:
                      $reg = 'QA';
                      break;
                }

                if ($gender) {
                    if ($regType && $regType !== 'QA') {
                      $reg .= $regNo;
                      $reg .= '/';
                      $reg .= $regBatch;
                    }

                    if ($regType && $regType !== 'QA' && $regBatchSuffix) {
                      $reg .= '/' + strtoupper($regBatchSuffix);
                    }
                }
                // we will do this again in the back-end
                // for now simply display
                return $reg;
            }
            // if we are given a numeric ID, 
            // we are to send back the fetched data from the DB
            else if (is_numeric($param))
            {
                $donor = self::find($id);
                if($donor)
                    return $donor->reg_complete;
            }
        }
    }


    static function findNext($id = '')
    {
        if($id != '')
        {
            return Donor::orderBy('id','ASC')->where('id','>',$id)->first();
        }
    }

    static function findPrev($id = '')
    {
        if($id != '')
        {
            return Donor::orderBy('id','DESC')->where('id','<',$id)->first();
        }
    }

// EOF
}
