<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// use App\Http\Requests;

use Dingo\Api\Http\Request;

class TestCaseController extends Controller {

	public function matrix(Request $request) {
		return response()->json(['test' => 'this is just a test'], 200);
	}
}
