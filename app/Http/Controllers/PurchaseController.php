<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Gateway;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Supplier;
use Exception;
use App\Models\Purchase;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('per_page', 10);
        $collection = Purchase::with('supplier:id,name')
            ->when($request->supplier_id, function ($query, $id) {
                $query->where('supplier_id', $id);
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

        return view('purchase.index', compact('collection'));
    }


    public function create()
    {
        $suppliers = Supplier::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();
        return view('purchase.create',compact('suppliers','categories'));
    }

    public function store(Request $request, StockService $stockService)
    {
        $this->validateInputs($request);
        try {
            DB::beginTransaction();
            $data = $request->except('purchase_items');
            $data['invoice_no'] = uniqid();
            $data['status'] = 'received';
            $purchase = Purchase::create($data);

            $items = $request->input('purchase_items');
            $purchase->purchase_details()->createMany($items);

            if ($data['status'] == 'received') {
                $stockService->updateOnPurchase($items);
            }

            if ($request->has('gateway_id')){
                $payment_method = Gateway::find($request->gateway_id);
                if ($payment_method){
                    $payment_method->balance -= $request->grand_total;
                    $payment_method->save();
                }
            }
            DB::commit();
            return redirect()->route('purchase.index')->with('success', 'Successfully completed purchase!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }




    public function edit(Purchase $purchase)
    {
        return view('purchase.edit');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load([
            'supplier:id,name,address,phone',
            'purchase_details.product:id,name,image',
        ]);
        return view('purchase.view', compact('purchase'));
    }

    public function invoice($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->load([
            'supplier:id,name,address,phone',
            'purchase_details.product:id,name,image',
        ]);
        return view('purchase.invoice', compact('purchase'));
    }

    public function update(Request $request, Purchase $purchase, StockService $stockService)
    {
        $this->validateInputs($request, $purchase->id);

        try {
            DB::beginTransaction();

            $prev_status = $purchase->status;
            $data = $request->except('purchase_details');

            if ($prev_status == 'received' && $data['status'] == 'pending') {
                throw new Exception("It's already received.", 422);
            }

            $purchase->update($data);

            if ($prev_status == 'received') {
                $prev_items = $purchase->purchase_details()
                    ->select('product_id', 'quantity')
                    ->get();
            }

            $items = $request->input('purchase_details');
            $purchase->syncHasMany('purchase_details', $items);

            if ($prev_status == 'received') {
                $stockService->updateOnPurchase($items, $prev_items);
            }

            if ($prev_status == 'pending' && $data['status'] == 'received') {
                $stockService->updateOnPurchase($items);
            }

            DB::commit();
            return response()->json(['message' => 'Successfully updated.']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Purchase $purchase, StockService $stockService)
    {
        try {
            DB::beginTransaction();
            $prev_items = $purchase->purchase_details()
                ->select('product_id', 'quantity')
                ->get();
            $stockService->updateQuantity(
                $prev_items,
                'decrement',
                function ($item, $stock) use ($stockService) {
                    if ($item['quantity'] > $stock->quantity) {
                        $stockService->insufficientStockException($item['product_id']);
                    }
                }
            );

            $purchase->purchase_details()->delete();
            $purchase->delete();

            DB::commit();
            return redirect()->back()->with('success','Successfully deleted');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function validateInputs($request)
    {
        $request->validate([
            'supplier_id' => 'nullable|numeric',
            'gateway_id' => 'required',
            'purchase_date' => 'required|date',
            'purchase_items' => 'required|array',
        ],[
            'purchase_items:required' => 'Purchase items cannot be empty!'
        ]);
    }
}
