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
    	// if we're being asked for available donors' list, return that
    	if($request->has('listType') && $request->get('listType') == 'available')
    	{
    		$q = Donor::select('donors.*')->distinct();
    		$q->leftJoin('donations', 'donors.id', '=', 'donations.donor_id');
    		$q->where(function($qu){
    			$qu->where('donations.date', '<=', Carbon::now()->subMonth(4)); // last donation at least 4 months ago
    			$qu->orWhereNull('donations.date');
    		});
    		if($request->has('groups'))
	    		$q->whereIn('blood_group',$request->get('groups'));

    		$q->where('donors.can_donate',1);
    		$q->where('donors.available',1);
    		$q->orderBy('donations.date','ASC');
    		$q->with('lastDonation','area');
    		$result = $q->paginate(10);
    		return response()->json($result);

    	}
    	else
    	{
    		// else, return a full donors list
	    	$q = Donor::with('area','lastDonation');

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


    public function getDonorCount()
    {
    	return response()->json(Donor::getAvailableCount());
    }


    public function store(Request $request)
    {
    	// validation rules
    	$rules = [
    		'name'		=>	'bail|required|min:3|max:250',
    		'gender'	=>	'required|in:male,female',
    		'blood_group'	=>	'required|in:' . implode(',', Donor::getGroups()),
    		'can_donate'	=>	'required|boolean',
    		'available'		=>	'required|boolean',
    		'unavailable_till'	=>	'nullable|date',
    		'dob'		=>	'nullable|date',
    		'reg_type'	=>	'required|in:' . implode(',', Donor::getTypes()),
    		'reg_no'	=>	'nullable|integer',
    		'reg_batch'	=>	'nullable|integer',
    		'phone_primary'	=>	'required|min:5|max:20',
    		'religion'	=>	'required|in:' . implode(',', Donor::getReligions()),
    		'area_id'	=>	'required|exists:areas,id',
    		'address'	=>	'nullable|min:3|max:250'
    	];

    	$this->validate($request, $rules);

    	// no errors
    	// time to save
    	$data = $request->all(); // all input

    	// set these to null if not provided
    	// if provided, we store them as comma separated strings
    	$data['phone_secondary'] = count($data['phone_secondary']) ? implode(',', $data['phone_secondary']) : null; 
    	$data['phone_request'] = count($data['phone_request']) ? implode(',', $data['phone_request']) : null; 

    	// format all dates
    	$data['dob'] = ($data['dob']) ? Carbon::parse($data['dob'])->toDateString() : null;
    	$data['unavailable_till'] = ($data['unavailable_till']) ? Carbon::parse($data['unavailable_till'])->toDateString() : null;

    	// prepare the complete reg no
    	$data['reg_complete'] = Donor::getCompleteReg($data);


    	try
    	{
    		$donor = Donor::create($data);
    	}
    	catch(\Exception $e)
    	{
    		return response()->json(['success' => false, 'error' => $e],400);
    	}

    	if($donor)
    		return response()->json(['success' => true, 'donor_id' => $donor->id], 200);

    	
    }


    public function show($id='')
    {
    	if($id !== '')
    	{
    		$data['current'] 	= Donor::with('area','donations','totalDonations')->find($id);
    		$data['next'] 	= Donor::findNext($id);
    		$data['prev'] 	= Donor::findPrev($id);

    		return response()->json($data,200);
    	}
    }


    // EOF
}
