<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $gateway = Gateway::latest()
            ->when($request->field && $request->keyword, function ($query) use ($request) {
                $query->where($request->field, 'like', '%' . $request->keyword . '%');
            })
            ->paginate($limit);
        return view('gateway.index', compact('gateway'));
    }

    public function create()
    {
        return view('gateway.create');
    }

    public function store(Request $request)
    {
        $this->validateInputs($request);
        try {
            $data = $request->only('name','balance','status');
            Gateway::create($data);
            return redirect()->route('gateway.index')->with('success', 'Successfully created');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit(Gateway $gateway)
    {
        return view('gateway.edit', compact('gateway'));
    }


    public function update(Request $request, Gateway $gateway)
    {
        $this->validateInputs($request, $gateway->id);
        try {
            $data = $request->only('name','balance','status');
            $gateway->update($data);
            return redirect()->route('gateway.index')->with('success', 'Successfully updated');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Gateway $gateway)
    {
        try {
            $gateway->delete();
            return redirect()->route('gateway.index')->with('success', 'Successfully deleted');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function validateInputs($request, $id = null)
    {
        $request->validate([
            'name' => 'required',
            'balance' => 'required',
        ]);
    }
}
