<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// use App\Http\Requests;

use Dingo\Api\Http\Request;

class TestCaseController extends Controller {

	/*
	 * @description: matrix testcases handler.
	 * creates a matrix per testcase and applies all operations over it.
	 * @return: array of 'query' operations per testcase.
	 */
	public function matrix(Request $request) {


		$queries = []; // query operations results to return
    foreach ($request['testcases'] as $testcase) {

			// current testcase matrix generation
      $matrix = [];
	    $queries[] = [];
      if(count($testcase['operations']) < 1) {
        $queries[count($queries) - 1] = 'No operations found for this testcase';
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

			// matrix operations execution
      $queryOperationQuantity = 0;
      foreach ($testcase['operations'] as $operation) {

				$op = explode(' ', $operation);
        if ($op[0] === 'UPDATE') {
					if (!$this->update($matrix, $op, $testcase['n']))
						return response()->json(
							['error' => 'wrong update operation format'], 400
						);
				}
        else if ($op[0] === 'QUERY') {
					if (!$this->query(
								$matrix,
								$op,
								$testcase['n'],
								$queries[count($queries) - 1]
							))
						return response()->json(
							['error' => 'wrong query operation format'], 400
						);
          $queryOperationQuantity++;
        }
        else
					return response()->json(
						['error' => 'one of the operations type is not valid'], 400
					);
			}

			if ($queryOperationQuantity === 0)
				$queries[count($queries) - 1][] = 'No query operations found';
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
