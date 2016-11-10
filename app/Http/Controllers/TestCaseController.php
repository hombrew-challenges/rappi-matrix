<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// use App\Http\Requests;

use Dingo\Api\Http\Request;

class TestCaseController extends Controller {

	/*
	 * @description: matrix testcases handler.
	 * creates a matrix per testcase and applies all operations over it.
	 * @return: array of 'query' operations results arrays.
	 */
	public function matrix(Request $request) {

		// Error handling
    if (!isset($request['testcases']))
      return response()->json(['error' => 'testcases were not provided'], 400);
    if (!is_array($request['testcases']))
      return response()->json(['error' => 'testcases is not an array'], 400);
    if (count($request['testcases']) < 1)
      return response()->json(['error' => 'testcases array is empty'], 400);

		$queries = []; // query operations results to return
    foreach ($request['testcases'] as $tkey=>$testcase) {

			// current testcase error handling
      if (!isset($testcase['n']))
        return response()->json(['error' => 'n was not provided in testcase '.strval($tkey+1)], 400);
      if (!is_numeric($testcase['n']) || $testcase['n'] < 1 || $testcase['n'] != round($testcase['n']) )
        return response()->json(['error' => 'n is not a positive integer in testcase '.strval($tkey+1)], 400);
      if (!isset($testcase['operations']))
        return response()->json(['error' => 'operations were not provided in testcase '.strval($tkey+1)], 400);
      if (!is_array($testcase['operations']))
        return response()->json(['error' => 'operations is not an array in testcase '.strval($tkey+1)], 400);

			// current testcase matrix generation
      $matrix = [];
	    $queries[] = [];
      if(count($testcase['operations']) < 1) {
        $queries[count($queries) - 1][] = 'No operations found for testcase '.strval($tkey+1);
        continue;
      }
      for ($i=0; $i < $testcase['n']; $i++) {
        $matrix[] = [];
        for ($j=0; $j < $testcase['n']; $j++) {
          $matrix[$i][] = [];
          for ($k=0; $k < $testcase['n']; $k++) {
            $matrix[$i][$j][] = 0;
          }
        }
      }

			// current matrix operations execution
      $queryOperationsQuantity = 0;
      foreach ($testcase['operations'] as $opkey=>$operation) {

				// current operation error handling
        if(!is_string($operation))
          return response()->json(['error' => 'operation '.strval($opkey+1).' in testcase '.strval($tkey+1).' is not a string'], 400);

				$op = explode(' ', $operation);
        if ($op[0] === 'UPDATE') {
					if (!$this->update($matrix, $op, $testcase['n']))
						return response()->json(['error' => 'wrong format for operation '.strval($opkey+1).' of type UPDATE in testcase '.strval($tkey+1)], 400);
				}
        else if ($op[0] === 'QUERY') {
					if (!$this->query(
								$matrix,
								$op,
								$testcase['n'],
								$queries[count($queries) - 1]
							))
						return response()->json(['error' => 'wrong format for operation '.strval($opkey+1).' of type QUERY in testcase '.strval($tkey+1)], 400);
          $queryOperationsQuantity++;
        }
        else
					return response()->json(
						['error' => 'operation '.strval($opkey+1).' type of testcase '.strval($tkey+1).' is not valid'], 400
					);
			}

			if ($queryOperationsQuantity === 0)
				$queries[count($queries) - 1][] = 'No query operations found for testcase '.strval($tkey+1);
		}

		return response()->json($queries);
	}

	/*
	 * @description: update operation handler.
	 * updates (x, y, z) position of the matrix with w.
	 * @param {array} $matrix: reference to the current matrix.
	 * @param {array} $op: array with the current operation values.
	 * @param {integer} $n: matrix sides length.
	 * @return: true or false if operation is valid or invalid, respectively.
	 */
	private function update(& $matrix, $op, $n) {

		// error_log('hola');
    // current operation error handling
    if (count($op) !== 5)
      return false;
    for($i = 1; $i < 5; $i++)
      if (!is_numeric($op[$i])
					|| ($op[$i] < 1 && $i !== 4)
					|| $op[$i] != round($op[$i])
					|| ($op[$i] > $n && $i !== 4)) {
        return false;
    }

		// current operation
  	$matrix[$op[1]-1][$op[2]-1][$op[3]-1] = $op[4];
    return true;
  }

	/*
	 * @description: query operation handler.
	 * adds every position content within (x1, y1, z1) and (x2, y2, z2).
	 * @param {array} $matrix: value of the current matrix.
	 * @param {array} $op: array with the current operation values.
	 * @param {integer} $n: matrix sides length.
	 * @param {array} $queries: reference to the current testcase query
		 operations results array.
	 * @return: true or false if operation is valid or invalid, respectively.
	 */
	private function query($matrix, $op, $n, & $queries) {

    // current operation error handling
    if (count($op) !== 7)
      return false;
    for($i = 1; $i < 7; $i++) {
      if (!is_numeric($op[$i])
					|| $op[$i] < 1
					|| $op[$i] != round($op[$i])
					|| $op[$i] > $n)
        return false;
    }

		// current operation
  	$q = 0;
  	for($i = $op[1]-1; $i < $op[4]; $i++) {
  		for($j = $op[2]-1; $j < $op[5]; $j++) {
  			for($k = $op[3]-1; $k < $op[6]; $k++) {
  				$q += $matrix[$i][$j][$k];
  			}
  		}
  	}
  	$queries[] = $q;
    return true;
  }
}
