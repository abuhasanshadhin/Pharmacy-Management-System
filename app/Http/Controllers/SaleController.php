<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Gateway;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use Exception;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('per_page', 10);
        $data['collection'] = Sale::with('customer:id,name')
            ->when($request->customer_id, function ($query, $id) {
                $query->where('customer_id', $id);
            })
            ->when($request->date_range, function ($query, $dates) {
                $query->whereDate('created_at', '>=', $dates[0] ?? null)
                    ->whereDate('created_at', '<=', $dates[1] ?? null);
            })
            ->when($request->field && $request->keyword, function ($query) use ($request) {
                $query->where($request->field, 'like', '%' . $request->keyword . '%');
            })
            ->latest()
            ->paginate($limit);

        return view('sale.index')->with($data);
    }

    public static function products($keyword = null, $category_id = null)
    {
        $date = date('Y-m-d');
        return Product::with('batches','stock')
            ->when(!empty($category_id), function ($q) use ($category_id) {
                $q->where('category_id', $category_id);
            })
            ->when(isset($keyword), function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            })
            ->whereHas('batches', function ($q) use ($date) {
                $q->where('expire_date', '>=', $date);
            })
            ->withCount(['stock as total_quantity' => function ($q) {
                $q->select(DB::raw('sum(quantity)'));
            }])
            ->latest()
            ->limit(20)
            ->get();
    }

    public function create()
    {
        return view('sale.create');
    }

    public function store(Request $request, StockService $stockService)
    {
        $this->validateInputs($request);
        try {
            DB::beginTransaction();
            $data = $request->except('items');
            $data['invoice_number'] = uniqid();
            $data['sale_date'] = now();
            $data['tax_value_type'] = 'fixed';
            $data['status'] = 'sold';
            $sale = Sale::create($data);

            $items = $request->input('items');
            $sale->sale_details()->createMany($items);

            if ($request->has('gateway_id')){
                $payment_method = Gateway::find($request->gateway_id);
                if ($payment_method){
                    $payment_method->balance += $request->total;
                    $payment_method->save();
                }
            }
            if ($data['status'] == 'sold') {
                $stockService->updateOnSale($items);
            }

            DB::commit();
            Session::put('lastInvoiceId', $sale->id);
            return redirect()->route('sale.create')->with('success', 'Successfully created.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        $sale->load([
            'customer:id,name,email,phone,address',
            'sale_details.product:id,name,image,unit_id',
            'sale_details.product.unit:id,name,short_name',
            'sale_details.product.stock:id,product_id,quantity',
        ]);

        return view('sale.view', compact('sale'));
    }

    public function invoice($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->load([
            'customer:id,name,email,phone,address',
            'sale_details.product:id,name,image,unit_id',
            'sale_details.product.unit:id,name,short_name',
            'sale_details.product.stock:id,product_id,quantity',
        ]);
        return view('sale.invoice', compact('sale'));
    }

    public function update(Request $request, Sale $sale, StockService $stockService)
    {
        $this->validateInputs($request, $sale->id);

        try {
            DB::beginTransaction();

            $prev_status = $sale->status;
            $data = $request->except('sale_details');

            if ($prev_status == 'sold' && $data['status'] == 'hold') {
                throw new Exception("It's already sold.", 422);
            }

            $sale->update($data);

            if ($prev_status == 'sold') {
                $prev_items = $sale->sale_details()
                    ->select('product_id', 'quantity')
                    ->get();
            }

            $items = $request->input('sale_details');
            $sale->syncHasMany('sale_details', $items);

            if ($prev_status == 'sold') {
                $stockService->updateOnSale($items, $prev_items);
            }

            if ($prev_status == 'hold' && $data['status'] == 'sold') {
                $stockService->updateOnSale($items);
            }

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated.',
                'sale_id' => $sale->id,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Sale $sale, StockService $stockService)
    {
        try {
            DB::beginTransaction();

            if ($sale->status == 'sold') {
                $prev_items = $sale->sale_details()
                    ->select('product_id', 'quantity')
                    ->get();

                $stockService->updateQuantity($prev_items, 'increment');
            }

            $sale->sale_details()->delete();
            $sale->delete();

            DB::commit();
            return redirect()->route('sale.index')->with('success', 'Successfully deleted');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('sale.index')->with('error', $e->getMessage());
        }
    }

    public function validateInputs($request)
    {
        $request->validate([
            'total' => 'required|numeric',
            'items' => 'required|array',
            'gateway_id' => 'required',
            'customer_id' => 'nullable|numeric',
        ],[
            'items.required' => 'Product cannot be empty! Please select minimum a product'
        ]);
    }
}
