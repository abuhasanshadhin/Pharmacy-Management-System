<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Sales.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function sales(Request $request)
    {
        $from_date = $request->input('from_date', now());
        $to_date = $request->input('to_date', date('Y-m-d', strtotime("+10 days")));

        $customers = Customer::select('id', 'name')->get();

        $sales = DB::table('sales as s')
            ->select(
                's.id',
                's.invoice_number',
                's.subtotal',
                's.tax',
                's.tax_value_type',
                's.discount',
                's.discount_value_type',
                's.total',
                's.customer_id',
                'c.name as customer_name',
                'c.phone as customer_phone'
            )
            ->selectRaw("date_format(s.sale_date, '%d %M, %Y') as sale_date")
            ->leftJoin('customers as c', 'c.id', '=', 's.customer_id')
            ->where('s.status', 'sold')
            ->when($request->customer_id, function ($query, $id) {
                $query->where('s.customer_id', $id);
            })
            ->when($from_date, function ($query, $from_date) {
                $query->whereDate('s.created_at', '>=', $from_date ?? null);
            })
            ->when($to_date, function ($query, $to_date) {
                $query->whereDate('s.created_at', '<=', $to_date ?? null);
            })
            ->when($request->column && $request->search_value, function ($query) use ($request) {
                $query->where(sprintf('s.%s', $request->column), 'like', '%' . $request->search_value . '%');
            })
            ->get();
        return view('report.sale', compact('sales', 'from_date', 'to_date', 'customers'));
    }

    /**
     * Purchases.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function purchases(Request $request)
    {
        $from_date = $request->input('from_date', now());
        $to_date = $request->input('to_date', date('Y-m-d', strtotime("+10 days")));

        $suppliers = Supplier::select('id', 'name')->get();
        $purchases = DB::table('purchases as p')
            ->select(
                'p.id',
                'p.invoice_no',
                'p.reference',
                'p.subtotal',
                'p.tax',
                'p.tax_value_type',
                'p.discount',
                'p.discount_value_type',
                'p.grand_total',
                'p.supplier_id',
                's.name as supplier_name',
                's.phone as supplier_phone'
            )
            ->selectRaw("date_format(p.purchase_date, '%d %M, %Y') as purchase_date")
            ->leftJoin('suppliers as s', 's.id', '=', 'p.supplier_id')
            ->where('p.status', 'received')
            ->when($request->supplier_id, function ($query, $id) {
                $query->where('p.supplier_id', $id);
            })
            ->when($from_date, function ($query, $from_date) {
                $query->whereDate('p.created_at', '>=', $from_date ?? null);
            })
            ->when($to_date, function ($query, $to_date) {
                $query->whereDate('p.created_at', '<=', $to_date ?? null);
            })
            ->when($request->column && $request->search_value, function ($query) use ($request) {
                $query->where(sprintf('p.%s', $request->column), 'like', '%' . $request->search_value . '%');
            })
            ->get();
        return view('report.purchase', compact('purchases', 'from_date', 'to_date', 'suppliers'));
    }
}
