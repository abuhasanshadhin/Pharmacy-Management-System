<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Stock;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::now();

        $data['today_purchase'] = Purchase::whereDate('created_at', $today)->sum('grand_total');
        $data['today_sale'] = Sale::whereDate('created_at', $today)->sum('total');

        $sales = DB::table('sales')
            ->whereDate('sales.created_at', $today)
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('purchase_details', 'sale_details.purchase_details_id', '=', 'purchase_details.id')
            ->select(DB::raw('SUM((sale_details.quantity * sale_details.price) - (sale_details.quantity * purchase_details.purchase_price)) AS profit'))
            ->first();
        $data['today_profit'] = $sales->profit;

        $data['total_medicine'] = Product::count();
        $data['total_sales'] = Sale::query()->sum('total');
        $data['total_purchase'] = Purchase::query()->sum('grand_total');
        $data['total_stock'] = Stock::query()->sum('quantity');
        $data['donut_chart'] = $this->chartReport();
        return view('dashboard')->with($data);
    }


    public function chartReport()
    {
        $salesData = Sale::selectRaw('MONTH(`sale_date`) AS month, SUM(total) AS total_amount')
            ->groupBy('month')
            ->get();

        $purchasesData = Purchase::selectRaw('MONTH(`purchase_date`) AS month, SUM(grand_total) AS total_amount')
            ->groupBy('month')
            ->get();

        $sales = [];
        $purchases = [];
        $profits = [];

        foreach ($salesData as $sale) {
            $sales[$sale->month] = $sale->total_amount;
        }

        foreach ($purchasesData as $purchase) {
            $purchases[$purchase->month] = $purchase->total_amount;
        }

        for ($i = 1; $i <= 12; $i++) {
            if (!isset($sales[$i])) {
                $sales[$i] = 0;
            }
            if (!isset($purchases[$i])) {
                $purchases[$i] = 0;
            }
        }

        foreach ($sales as $month => $saleAmount) {
            $profit = $saleAmount - ($purchases[$month] ?? 0);
            $profit = max(0, $profit); // Ensure profit is positive or zero
            $profits[$month] = $profit;
        }

        $options = [
            'series' => [
                ['name' => 'Sales', 'data' => array_values($sales)],
                ['name' => 'Purchase', 'data' => array_values($purchases)],
                ['name' => 'Profit', 'data' => array_values($profits)],
            ],
            'chart' => [
                'type' => 'bar',
                'height' => 350
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                    'columnWidth' => '55%',
                    'endingShape' => 'rounded'
                ],
            ],
            'dataLabels' => [
                'enabled' => false
            ],
            'stroke' => [
                'show' => true,
                'width' => 2,
                'colors' => ['transparent']
            ],
            'xaxis' => [
                'categories' => [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ]
            ],
            'fill' => [
                'opacity' => 1
            ],
            'tooltip' => [
                'y' => [
                    'formatter' => "function (val) {
                return '$ ' + val + ' thousands';
            }"
                ]
            ]
        ];
        return $options;
    }
}
