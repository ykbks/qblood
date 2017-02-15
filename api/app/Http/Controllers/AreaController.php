<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donor;
use App\Area;

class AreaController extends Controller
{
    public function index()
    {
    	$areas = Area::orderBy('parent','asc')->orderBy('name','asc')->get();
    	$list = [];

    	if(!empty($areas))
    	{
    		foreach ($areas as $area) {
    			$arr = [];
    			$arr['value'] = $area->id;
    			$arr['label'] = $area->name;
    			if(!is_null($area->parentArea))
    			{
    				$arr['label'] .= ' - ' . $area->parentArea->name;
    			}

    			$list[] = $arr;

    		}
    	}

    	return response()->json($list,200);
    }
}
