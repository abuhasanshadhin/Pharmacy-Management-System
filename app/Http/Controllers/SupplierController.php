<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $suppliers = Supplier::select('id','name','contact_person_name','email','phone','address','payable','status')->latest()
            ->when($request->field && $request->keyword, function ($query) use ($request) {
                $query->where($request->field, 'like', '%' . $request->keyword . '%');
            })
            ->paginate($limit);

        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('supplier.create');

    }

    public function store(Request $request)
    {
        $this->validateInputs($request);
        try {
            $data = $request->only('name', 'contact_person_name', 'email', 'phone', 'address', 'status', 'payable');
            $data['payable'] = $data['payable'] ?? 0;
            Supplier::create($data);
            return redirect()->route('supplier.index')->with('success', 'Supplier created successfully');
        } catch (Exception $e) {
            return redirect()->route('supplier.create')->with('error', $e->getMessage());
        }
    }


    public function edit(Supplier $supplier)
    {
        return view('supplier.edit',compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $this->validateInputs($request, $supplier->id);

        try {
            $data = $request->only('name', 'contact_person_name', 'email', 'phone', 'address', 'status','payable');
            $supplier->update($data);
            return redirect()->route('supplier.index')->with('success','Supplier updated successfully');
        } catch (Exception $e) {
            return redirect()->route('supplier.edit')->with('error',$e->getMessage());
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return redirect()->route('supplier.index')->with('success','Supplier deleted successfully');
        } catch (Exception $e) {
            return redirect()->route('supplier.index')->with('error',$e->getMessage());
        }
    }

    public function validateInputs($request, $id = null)
    {
        $request->validate([
            'name' => 'required',
            'contact_person_name' => 'required',
            'email' => 'required|email|unique:suppliers,email,' . $id . ',id',
            'phone' => 'required|unique:suppliers,phone,' . $id . ',id',
            'payable' => 'nullable|numeric',
        ]);
    }
}
