<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Donor;

class DonorController extends Controller
{
    public function index()
    {
    	$donors = Donor::paginate(10);
    	return response()->json($donors);
    }
}
