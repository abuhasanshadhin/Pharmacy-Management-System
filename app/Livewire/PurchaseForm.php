<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Gateway;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class PurchaseForm extends Component
{
    public $suppliers = [];
    public $categories = [];
    public $products = [];
    public $gatewayes = [];
    public $purchase_date;

    public $keyword;
    public $category_id;
    public $is_visibleProductList = false;

    public $carts = [];

    public $tax = 0;
    public $tax_value_type ='percent';
    public $discount = 0;
    public $discount_value_type = 'percent';

    public function mount()
    {
        $this->suppliers = Supplier::select('id', 'name')->get();
        $this->categories = Category::select('id', 'name')->get();
        $this->purchase_date = date('Y-m-d');
        $this->gatewayes =  Gateway::where('status','active')->select('id','name')->get();

        $this->getProduct();
    }


    public function ADDTOCART(Product $product)
    {
        $productId = $product->id;
        if (array_key_exists($productId, $this->carts)){
            $this->carts[$productId]['quantity'] += 1;
        }else{
            $this->carts[$productId] = [
                'product_id' => $product->id,
                'sku' => $product->sku,
                'name' => $product->name,
                'image' => $product->image,
                'purchase_price' => $product->purchase_price,
                'sale_price' => $product->sale_price,
                'quantity' => 1,
                'batch' => '',
                'expire_date' => null,
                'discount' => 0,
                'discount_type' => 'percent',
            ];
        }
    }

    public function updateQuantity($productId, $quantity)
    {
        $this->carts[$productId]['quantity'] = $quantity;
    }

    public function removeCartItem($productId)
    {
        unset($this->carts[$productId]);
    }

    public function visibleProductList()
    {
        $this->is_visibleProductList = true;
    }

    public function hideSearchList()
    {
        $this->is_visibleProductList = false;
    }

    public function getProduct()
    {
        $products = Cache::rememberForever(Product::class, function () {
            return Product::with('category:id,name', 'unit:id,name,short_name')
                ->when($this->category_id, function ($query, $value) {
                    $query->where('category_id', $value);
                })
                ->where('status', 'active')
                ->get()
                ->toArray();
        });

        if (!empty($this->keyword)) {
            $products = collect($products)->filter(function ($product) {
                $keyword = strtolower($this->keyword);
                $product_name = strtolower($product['name']);
                return stripos($product_name, $keyword) !== false;
            })->values();
            $this->products = $products;
        }
        $this->products = $products;
    }

    public function render()
    {
        return view('livewire.purchase-form',[
            'subtotalAmount' => $this->calculateTotalAmount(),
            'medicineDiscountAmount' => $this->calculateTotalDiscount(),
        ]);
    }

    private function calculateTotalAmount()
    {
        $total = 0;

        foreach ($this->carts as $cartItem) {
            $subtotal = $cartItem['purchase_price'] * $cartItem['quantity'];
            $discountAmount = calculateDiscountAmount($subtotal, $cartItem['discount'], $cartItem['discount_type']);
            $total += ($subtotal - $discountAmount);
        }

        return $total;
    }
    private function calculateTotalDiscount()
    {
        $total = 0;

        foreach ($this->carts as $cartItem) {
            $subtotal = $cartItem['purchase_price'] * $cartItem['quantity'];
            $discountAmount = calculateDiscountAmount($subtotal, $cartItem['discount'], $cartItem['discount_type']);
            $total += $discountAmount;
        }

        return $total;
    }
}
