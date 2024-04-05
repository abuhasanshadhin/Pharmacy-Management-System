<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $units = Unit::latest()->paginate($request->input('limit', 10));
        return view('unit.index',compact('units'));
    }

    public function create()
    {
        return view('unit.create');
    }

    public function store(Request $request)
    {
        $this->validateInputs($request);

        try {
            $data = $request->only('name', 'short_name', 'status');
            Unit::create($data);
            return redirect()->route('unit.index')->with('success','Unit created successfully');
        } catch (Exception $e) {
            return redirect()->route('unit.create')->with('error',$e->getMessage());
        }
    }

    public function edit(Unit $unit)
    {
        return view('unit.edit',compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $this->validateInputs($request, $unit->id);

        try {
            $data = $request->only('name', 'short_name', 'status');
            $unit->update($data);
            return redirect()->route('unit.index')->with('success','Unit updated successfully');
        } catch (Exception $e) {
            return redirect()->route('unit.edit')->with('error',$e->getMessage());
        }
    }

    public function destroy(Unit $unit)
    {
        try {
            $unit->delete();
            return redirect()->route('unit.index')->with('success','Unit deleted successfully');
        } catch (Exception $e) {
            return redirect()->route('unit.index')->with('error',$e->getMessage());
        }
    }

    public function validateInputs($request, $id = null)
    {
        $request->validate([
            'name' => 'required|unique:units,name,' . $id . ',id',
            'short_name' => 'nullable|unique:units,name,' . $id . ',id',
        ]);
    }
}
