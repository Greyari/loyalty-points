<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return response()->json(Customer::latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'required|string|max:20|unique:customers,phone',
        ]);

        return response()->json(Customer::create($request->all()), 201);
    }

    public function show($id)
    {
        return response()->json(Customer::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name'   => 'sometimes|string|max:255',
            'phone'  => 'sometimes|string|max:20|unique:customers,phone,' . $id,
        ]);

        $customer->update($request->all());

        return response()->json($customer);
    }

    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();

        return response()->json(['message' => 'Customer deleted']);
    }
}
