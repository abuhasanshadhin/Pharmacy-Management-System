<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('per_page', 10);

        $collection = Stock::with('product:id,name')
            ->when($request->field && $request->keyword, function (Builder $query) use ($request) {
                if (Str::startsWith($request->field, 'product_')) {
                    $query->whereHas('product', function (Builder $query) use ($request) {
                        $query->where('name', 'like', '%' . $request->search_value . '%');
                    });
                }
            })
            ->where('quantity', '>', 0)
            ->latest()
            ->paginate($limit);

        return view('stock.index',compact('collection'));
    }
}
