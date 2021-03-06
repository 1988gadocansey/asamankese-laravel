<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use DB;
use Response;

class SearchController extends Controller
{
    function index(){
        return view('autocomplete');
    }

    public function autocomplete(){
	$term = Input::get('term');
	
	$results = array();
	
	 
	$queries = DB::table('tpoly_students')
                ->where('INDEXNO','LIKE', '%'.$term.'%')
                ->orwhere('STNO', 'LIKE', '%'.$term.'%')
		->orwhere('SURNAME', 'LIKE', '%'.$term.'%')
		->orWhere('FIRSTNAME', 'LIKE', '%'.$term.'%')
                ->orWhere('OTHERNAMES', 'LIKE', '%'.$term.'%')
                ->orWhere('NAME', 'LIKE', '%'.$term.'%')
                
		->take(500)->get();
	
	foreach ($queries as $query)
	{
		if( $query->INDEXNO!=""){
			$results[] = [ 'id' => $query->ID, 'value' => $query->INDEXNO.','.$query->NAME ];
		}
		else{
	    $results[] = [ 'id' => $query->ID, 'value' => $query->STNO.','.$query->NAME ];
	}
	}
return Response::json($results);
}
 
} 