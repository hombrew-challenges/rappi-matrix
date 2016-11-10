<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
// use Log;

class TestCaseTest extends TestCase {
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPost_testcasesNotProvided() {

      $this
        ->post('api/testcases', [])
        ->seeStatusCode(400)
        ->seeJson(['error' => 'testcases were not provided']);

      // fwrite(STDOUT, $data['error']);
    }

    public function testPost_testcasesNotAnArray() {
      $obj = ['testcases' => 'a'];
      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'testcases is not an array']);
    }

    public function testPost_testcasesArrayIsEmpty() {
      $obj = ['testcases' => []];
      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'testcases array is empty']);
    }

    public function testPost_nWasNotProvided() {
      $obj = [
        'testcases' => [
          []
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'n was not provided in testcase 1']);
    }

    public function testPost_nIsNotAPositiveInteger() {
      $obj = [
        'testcases' => [
          ['n' => 'a']
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'n is not a positive integer in testcase 1']);
    }

    public function testPost_operationsWereNotProvided() {
      $obj = [
        'testcases' => [
          ['n' => 2]
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'operations were not provided in testcase 1']);
    }

    public function testPost_operationsIsNotAnArray() {
      $obj = [
        'testcases' => [
          [
            'n' => 2,
            'operations' => 'a'
          ]
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'operations is not an array in testcase 1']);
    }

    public function testPost_operationsNotFound() {
      $obj = [
        'testcases' => [
          [
            'n' => 2,
            'operations' => []
          ]
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(200)
        ->seeJson(['No operations found for testcase 1']);
    }

    public function testPost_operationNotAString() {
      $obj = [
        'testcases' => [
          [
            'n' => 2,
            'operations' => [2]
          ]
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'operation 1 in testcase 1 is not a string']);
    }

    public function testPost_operationNotValid() {
      $obj = [
        'testcases' => [
          [
            'n' => 2,
            'operations' => ['update']
          ]
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'operation 1 type of testcase 1 is not valid']);
    }

    public function testPost_operationUpdateLesserInputQuantity() {
      $obj = [
        'testcases' => [
          [
            'n' => 2,
            'operations' => ['UPDATE 1 1 1']
          ]
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'wrong format for operation 1 of type UPDATE in testcase 1']);
    }

    public function testPost_operationUpdateGreaterInputQuantity() {
      $obj = [
        'testcases' => [
          [
            'n' => 2,
            'operations' => ['UPDATE 1 1 1 1 1']
          ]
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'wrong format for operation 1 of type UPDATE in testcase 1']);
    }

    public function testPost_operationUpdateInputsGreaterThanN() {
      $obj = [
        'testcases' => [
          [
            'n' => 2,
            'operations' => ['UPDATE 1 1 3 1']
          ]
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'wrong format for operation 1 of type UPDATE in testcase 1']);
    }

    public function testPost_operationQueryLesserInputQuantity() {
      $obj = [
        'testcases' => [
          [
            'n' => 2,
            'operations' => ['QUERY 1 1 1']
          ]
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'wrong format for operation 1 of type QUERY in testcase 1']);
    }

    public function testPost_operationQueryGreaterInputQuantity() {
      $obj = [
        'testcases' => [
          [
            'n' => 2,
            'operations' => ['QUERY 1 1 1 1 1 1 1']
          ]
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'wrong format for operation 1 of type QUERY in testcase 1']);
    }

    public function testPost_operationQueryInputsGreaterThanN() {
      $obj = [
        'testcases' => [
          [
            'n' => 2,
            'operations' => ['QUERY 1 1 1 5 1 4']
          ]
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(400)
        ->seeJson(['error' => 'wrong format for operation 1 of type QUERY in testcase 1']);
    }

    public function testPost_validTestcase() {
      $obj = [
        'testcases' => [
          [
            'n' => 3,
            'operations' => [
              'UPDATE 1 1 1 5',
              'UPDATE 2 2 2 5',
              'UPDATE 3 3 3 5',
              'QUERY 1 1 1 3 3 3',
              'UPDATE 1 2 3 5',
              'QUERY 1 1 1 3 3 3'
            ]
          ]
        ]
      ];

      $this
        ->post('api/testcases', $obj)
        ->seeStatusCode(200)
        ->seeJson([
          [
            15,
            20
          ]
        ]);
    }

}
