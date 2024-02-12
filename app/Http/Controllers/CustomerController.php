<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller {

    public $notFound;
    public $unauthorized;
    public function __construct() {
        $this->notFound =
            $this->generateResponse(
                message: "customer was not found",
                state: "failure",
                statusCode: 404
            );

        $this->unauthorized =
            $this->generateResponse(
                message: "unauthorized request",
                state: "failure",
                statusCode: 401
            );
    }


    /**
     * Display a listing of the resource.
     */
    public function index() {
        $customers = Customer::all();
        // laravel sets the default content-type header to text/html
        // this converts it to normal json similar to res.json in express
        return response()->json(
            $this->generateResponse(
                state: "success",
                statusCode: 200,
                data: $customers
            ),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        if (Customer::insert($request->all())) {
            return response()->json(
                $this->generateResponse(
                    message: "customer added successfully",
                    state: "success",
                    statusCode: 200
                ),
                200
            );
        }
        return response()->json(
            $this->generateResponse(
                message: "customer was not added",
                state: "failure",
                statusCode: 400
            ),
            400
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {
        if (!$this->verifyId($id)) {
            return response()->json($this->unauthorized, 401);
        }

        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(
                $this->notFound,
                404
            );
        }

        return response()->json(
            $this->generateResponse(
                state: "success",
                statusCode: 200,
                data: $customer
            ),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        if (!$this->verifyId($id)) {
            return response()->json($this->unauthorized, 401);
        }

        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(
                $this->notFound,
                404
            );
        }

        if (!$customer->update($request->all())) {
            return response()->json(
                $this->generateResponse(
                    message: "error while updating the customer",
                    state: "failure",
                    statusCode: 400
                ),
                400
            );
        }

        return response()->json(
            $this->generateResponse(
                message: "customer updated successfully",
                state: "success",
                statusCode: 200
            ),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        if (!$this->verifyId($id)) {
            return response()->json($this->unauthorized, 401);
        }

        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(
                $this->notFound,
                404
            );
        }

        if (!$customer->delete()) {
            return response()->json(
                $this->generateResponse(
                    message: "customer deletion failed",
                    state: "failure",
                    statusCode: 400
                ),
                400
            );
        }
        
        return response()->json(
            $this->generateResponse(
                message: "customer deleted successfully",
                state: "success",
                statusCode: 200
            ),
            200
        );
    }

    /**
     * Generate a response array with message, state, status code, and data.
     * This method maps the provided method arguments to the expected parameter
     * names and builds a response array.
     * 
     * @param string $message The message to include in the response
     * @param string $state The state to include in the response 
     * @param int $statusCode The status code to include in the response
     * @param mixed $data Additional data to include in the response
     * @return array The generated response array
     */
    public function generateResponse(
        string $message = null,
        string $state = null,
        int $statusCode = null,
        mixed $data = null
    ): array {
        // returns an assoc array containing the values of 
        //the variables passed to it
        $params = compact('message', 'state', 'statusCode', 'data');

        // Loop over each parameter
        foreach ($params as $key => $value) {
            // If the parameter is not empty, add it to the response array.
            if (!empty($value)) $response[$key] = $value;
        }

        // Return the response array, or null if the response array is empty
        return $response ?? null;
    }

    public function verifyId(mixed $id): bool {
        // ctype_digit returns checks if every char in the string is an integer
        return (0 < $id && ctype_digit($id));
    }
}
