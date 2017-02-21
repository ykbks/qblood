<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Donor;
use App\Area;

class DonorController extends Controller
{
    public function index(Request $request)
    {
    	$q = Donor::with('area');

    	$q->orderBy('name','asc');
    	if($request->has('available'))
    		$q->where('available',$request->get('available'));
    	if($request->has('can_donate'))
    		$q->where('can_donate',$request->get('canDonate'));
    	
    	if($request->has('regTypes'))
    	{
    		$regTypes = [];
    		foreach($request->get('regTypes') as $type)
    		{
    			$regTypes[] = strtolower($type);
    		}
    		$q->whereIn('reg_type',$regTypes);
    	}
    	if($request->has('groups'))
    		$q->whereIn('blood_group',$request->get('groups'));
    	if($request->has('areas'))
    		$q->whereIn('area_id',$request->get('areas'));
    	if($request->has('batches'))
    		$q->whereIn('reg_batch',$request->get('batches'));
    	if($request->has('religions'))
    		$q->whereIn('religion',$request->get('religions'));
    	
    	// handle the search term
    	if($request->has('term'))
    	{
    		$term = $request->get('term');
    		$q->where(function($qw) use($term){
	    		$qw->where('name','like','%'. $term .'%');
	    		$qw->orWhere('phone_primary','like','%'. $term .'%');
	    		$qw->orWhere('phone_secondary','like','%'. $term .'%');
	    		$qw->orWhere('phone_request','like','%'. $term .'%');
	    		$qw->orWhere('reg_complete','like','%'. $term .'%');

    		});
    	}
    	
    	

    	// echo $q->toSql();

    	$donors = $q->paginate(10);
    	$donors->each(function($row){
    		if($row->unavailable_till)
    		{
    			$row->unavailable_till = Carbon::parse($row->unavailable_till)->toFormattedDateString();
    		}
    	});
    	return response()->json($donors);
    }

    function getRegBatches()
    {
    	$list = [];
    	
    	$batches = Donor::select('reg_batch')
    				->orderBy('reg_batch','asc')
    				->distinct()
    				->get();

    	if(! $batches->isEmpty() )
    	{
    		foreach ($batches as $batch) {
    			$list[] = ['value' => $batch->reg_batch, 'label' => (string) $batch->reg_batch]; // we typecast because vue-select keeps trying to use .toLowerCase 
    		}
    	}

    	return response()->json($list, 200);
    }

    // EOF
}
