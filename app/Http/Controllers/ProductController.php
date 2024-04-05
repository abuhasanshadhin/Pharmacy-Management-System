<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $limit = $request->query('limit', 5);

        $products = Product::with('supplier:id,name', 'category:id,name', 'unit:id,name')
            ->when($request->category_id, function ($query, $value) {
                $query->where('category_id', $value);
            })
            ->when($request->unit_id, function ($query, $value) {
                $query->where('unit_id', $value);
            })
            ->when($request->supplier_id, function ($query, $value) {
                $query->where('supplier_id', $value);
            })
            ->when($request->field && $request->keyword, function ($query) use ($request) {
                $query->where($request->field, 'like', '%' . $request->keyword . '%');
            })
            ->latest()
            ->paginate($limit);

        $categories = Category::select('id', 'name')
            ->whereNull('parent_id')
            ->latest()
            ->get();
        $units = Unit::select('id', 'name', 'short_name')
            ->latest()
            ->get();
        $suppliers = Supplier::select('id', 'name')
            ->get();

        return view('product.index', compact('products','categories','units','suppliers'));

    }

    public function create()
    {
        $categories = Category::select('id', 'name')
            ->whereNull('parent_id')
            ->latest()
            ->get();
        $units = Unit::select('id', 'name', 'short_name')
            ->latest()
            ->get();
        $suppliers = Supplier::select('id', 'name')
            ->get();

        return view('product.create',compact('categories','units','suppliers'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function products(Request $request)
    {
        $products = Cache::rememberForever(Product::class, function () {
            return Product::with('category:id,name', 'unit:id,name,short_name')
                ->where('status', 'active')
                ->get()
                ->toArray();
        });

        if ($request->filled('keyword')) {
            if ($request->boolean('first')) {
                $product = collect($products)->filter(function ($product) use ($request) {
                    $keyword = strtolower($request->keyword);
                    $product_name = strtolower($product['name']);
                    return $product_name == $keyword || $product['sku'] == $keyword;
                })->first();

                if (is_array($product) && $request->boolean('withStock')) {
                    $stockService = new StockService();
                    $product['stock'] = $stockService->byProduct($product['id']);
                }

                return response()->json($product);
            } else {
                $products = collect($products)->filter(function ($product) use ($request) {
                    $keyword = strtolower($request->keyword);
                    $product_name = strtolower($product['name']);
                    return stripos($product_name, $keyword) !== false;
                })->values();

                return response()->json($products);
            }
        }
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateInputs($request);
        try {
            $data = $request->except('image');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $data['image'] = $this->upload($image, 'product_images');
            }
            Product::create($data);
            Cache::forget(Product::class);
            return redirect()->route('product.index')->with('success', 'Product created successfully');
        } catch (Exception $e) {
            return redirect()->route('product.create')->with('error', $e->getMessage());
        }
    }

    /**
     * Edit specified product
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */

    public function edit(Product $product)
    {
        $categories = Category::select('id', 'name')
            ->whereNull('parent_id')
            ->latest()
            ->get();
        $units = Unit::select('id', 'name', 'short_name')
            ->latest()
            ->get();
        $suppliers = Supplier::select('id', 'name')
            ->get();

        return view('product.edit',compact('product','categories','units','suppliers'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load([
            'category:id,name',
            'unit:id,name',
            'supplier:id,name',
        ]);

        return view('product.view',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validateInputs($request, $product->id);
        try {
            $data = $request->except('image');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $prev = $product->getRawOriginal('image');
                $data['image'] = $this->upload($image, 'product_images', $prev);
            }

            $product->update($data);
            Cache::forget(Product::class);
            return redirect()->route('product.index')->with('success', 'Product updated successfully');
        } catch (Exception $e) {
            return redirect()->route('product.edit')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $image = $product->getRawOriginal('image');
            $product->delete();
            if ($image && Storage::disk('public')->exists($image)) {
                Storage::disk('public')->delete($image);
            }
            Cache::forget(Product::class);
            return redirect()->route('product.index')->with('success', 'Product deleted successfully');
        } catch (Exception $e) {
            return redirect()->route('product.index')->with('error', $e->getMessage());
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
            'category_id' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'supplier_id' => 'required|numeric',
            'name' => 'required|unique:products,name,' . $id . ',id',
            'generaic_name' => 'required',
            'barcode' => 'required',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
        ]);
    }
}
