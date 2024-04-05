<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $limit = $request->input('per_page', 10);
        $categories = Category::with('parent:id,name')->latest()->paginate($limit);
        return view('category.index',compact('categories'));
    }


    public function create()
    {
        $categories = Category::select('id', 'name')
            ->whereNull('parent_id')
            ->latest()
            ->get();
        return view('category.create',compact('categories'));
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
            $data = $request->only('parent_id', 'name', 'status');
            Category::create($data);
            return redirect()->route('category.index')->with('success','Category created successfully');
        } catch (Exception $e) {
            return redirect()->route('category.create')->with('error',$e->getMessage());
        }
    }

    /**
     * Edit specified category
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response $response
     */
    public function edit(Category $category)
    {
        $categories = Category::select('id', 'name')
            ->whereNull('parent_id')
            ->latest()
            ->get();
        return view('category.edit',compact('categories','category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\POS\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validateInputs($request, $category->id);
        try {
            $data = $request->only('parent_id', 'name', 'status');
            $category->update($data);
            return redirect()->route('category.index')->with('success','Category updated successfully');
        } catch (Exception $e) {
            return redirect()->route('category.edit')->with('error',$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('category.index')->with('success','Category deleted successfully');
        } catch (Exception $e) {
            return redirect()->route('category.index')->with('error',$e->getMessage());
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
            'parent_id' => 'nullable|numeric',
            'name' => 'required|unique:categories,name,' . $id . ',id',
        ]);
    }
}
