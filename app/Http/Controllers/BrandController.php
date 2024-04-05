<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('per_page', 10);
        $brands = Brand::latest()->paginate($limit);
        return response()->json($brands);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateInputs($request);

        try {
            $data = $request->only('name', 'status');
            Brand::create($data);
            return response()->json(['message' => 'Successfully created.'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return response()->json($brand);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $this->validateInputs($request, $brand->id);

        try {
            $data = $request->only('name', 'status');
            $brand->update($data);
            return response()->json(['message' => 'Successfully updated.']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();
            return response()->json(['message' => 'Successfully deleted.']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Validate incoming request inputs
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function validateInputs($request, $id = null)
    {
        $request->validate([
            'name' => 'required|unique:brands,name,' . $id . ',id',
        ]);
    }
}
