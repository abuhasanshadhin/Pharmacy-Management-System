<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $prescription = Prescription::latest()
            ->when($request->field && $request->keyword, function ($query) use ($request) {
                $query->where($request->field, 'like', '%' . $request->keyword . '%');
            })
            ->paginate($limit);

        return view('prescription.index', compact('prescription'));
    }

    public function create()
    {
        return view('prescription.create');

    }

    public function store(Request $request)
    {
        $this->validateInputs($request);
        try {
            $data = $request->only('patient_name', 'patient_phone', 'patient_age', 'patient_address');
            $data['no'] = uniqid();
            $data['date'] = now();

            if ($request->hasFile('prescription_file')){
                $data['prescription_file'] = $this->upload($request->prescription_file,'prescription');
            }
            Prescription::create($data);
            return redirect()->route('prescription.index')->with('success', 'Prescription created successfully');
        } catch (\Exception $e) {
            return redirect()->route('prescription.create')->with('error', $e->getMessage());
        }
    }


    public function edit(Prescription $prescription)
    {
        return view('prescription.edit', compact('prescription'));
    }

    public function show(Prescription $prescription)
    {
        return view('prescription.view', compact('prescription'));
    }


    public function update(Request $request, Prescription $prescription)
    {
        $this->validateInputs($request, $prescription->id);
        try {
            $data = $request->only('patient_name', 'patient_phone', 'patient_age', 'patient_address');

            if ($request->hasFile('prescription_file')){
                $data['prescription_file'] = $this->upload($request->prescription_file,'prescription', $prescription->prescription_file);
            }
            $prescription->update($data);
            return redirect()->route('prescription.index')->with('success', 'Prescription updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('prescription.edit')->with('error', $e->getMessage());
        }
    }

    public function destroy(Prescription $prescription)
    {
        try {
            $prescription->delete();
            return redirect()->route('prescription.index')->with('success', 'Prescription deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('prescription.index')->with('error', $e->getMessage());
        }
    }

    public function validateInputs($request, $id = null)
    {
        $request->validate([
            'patient_name' => 'required',
            'patient_phone' => 'required',
            'patient_age' => 'required',
        ]);
    }
}
