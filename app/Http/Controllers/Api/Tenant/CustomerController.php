<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\Tenant\Customer\StoreCustomerRequest;
use App\Http\Requests\Tenant\Customer\UpdateCustomerRequest;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $customers
        ]);
    }

    public function show(Customer $customer)
    {
        return response()->json([
            'status' => 'success',
            'data' => $customer
        ]);
    }

    public function store(StoreCustomerRequest $request)
    {
        $validatedData = $request->validated();

        $customer = Customer::create($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $customer
        ], 201);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $validatedData = $request->validated();

        $customer->update($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $customer
        ]);
    }


    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Customer deleted successfully'
        ]);
    }
}
