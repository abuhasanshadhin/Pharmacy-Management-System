<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $customers = Customer::latest()
            ->when($request->field && $request->keyword, function ($query) use ($request) {
                $query->where($request->field, 'like', '%' . $request->keyword . '%');
            })
            ->paginate($limit);

        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        return view('customer.create');

    }

    public function store(Request $request)
    {
        $this->validateInputs($request);
        try {
            $data = $request->only('name', 'email', 'phone', 'address', 'status', 'due');
            Customer::create($data);
            return redirect()->route('customer.index')->with('success', 'Customer created successfully');
        } catch (Exception $e) {
            return redirect()->route('customer.create')->with('error', $e->getMessage());
        }
    }


    public function edit(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }


    public function update(Request $request, Customer $customer)
    {
        $this->validateInputs($request, $customer->id);
        try {
            $data = $request->only('name', 'email', 'phone', 'address', 'status', 'due');
            $customer->update($data);
            return redirect()->route('customer.index')->with('success', 'Customer updated successfully');
        } catch (Exception $e) {
            return redirect()->route('customer.edit')->with('error', $e->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return redirect()->route('customer.index')->with('success', 'Customer deleted successfully');
        } catch (Exception $e) {
            return redirect()->route('customer.index')->with('error', $e->getMessage());
        }
    }

    public function validateInputs($request, $id = null)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|nullable|email|unique:customers,email,' . $id . ',id',
            'phone' => 'required|unique:customers,phone,' . $id . ',id',
            'due' => 'nullable|numeric',
        ]);
    }
}
