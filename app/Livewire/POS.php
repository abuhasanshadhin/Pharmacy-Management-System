<?php

namespace App\Livewire;

use App\Http\Controllers\SaleController;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Gateway;
use App\Models\Product;
use App\Models\PurchaseDetails;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class POS extends Component
{
    public $products;
    public $categories = [];
    public $customers = [];
    public $gatewayes = [];
    public $customer = [];
    public $hasValidateError = false;

    public $cart = [];
    public $discount_amount = 0;
    public $discount_value_type = 'percent';
    public $search_keywords;
    public $category_id;
    public $saleInvoice;

    public function boot()
    {
        $lastInvoice = Session::get('lastInvoiceId');
        if ($lastInvoice){
            $sale = Sale::findOrFail($lastInvoice);
            $sale->load([
                'customer:id,name,email,phone,address',
                'gateway:id,name,balance',
                'sale_details.product:id,name,image,unit_id',
                'sale_details.product.unit:id,name,short_name',
                'sale_details.product.stock:id,product_id,quantity',
            ]);
        }
        $this->saleInvoice = $sale ?? [];

        $this->categories = Category::select('id','name')->latest('id')->get();
        $this->customers = Customer::select('id','name')->latest('id')->get();
        $this->getProducts();
        $this->gatewayes =  Gateway::where('status','active')->select('id','name')->get();
    }

    public function addNewCustomer()
    {
        Customer::create($this->customer);
        flash()->addPreset('entity_saved');
        return redirect()->to('/sale/create');
    }

    public function categoryChangeHandler($id = null)
    {
        $this->category_id = $id;
        $this->getProducts();
    }

    public function getProducts()
    {
        $this->products =  SaleController::products($this->search_keywords,$this->category_id);
    }


    public function ADDTOCART(Product $product)
    {
        $productId = $product->id;
        $batches = $product->batches()->select('id', 'batch')->get();
        if (array_key_exists($productId, $this->cart)){
            $this->cart[$productId]['quantity'] +=1;
        }else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'barcode' => $product->barcode,
                'sku' => $product->sku,
                'sale_price' => $product->sale_price,
                'tax' => $product->tax,
                'tax_value_type' => $product->tax_value_type,
                'batches' => $batches,
                'quantity' => 1,
                'batch_name' => null,
                'batch_id' => null,
                'expire_date' => null,
                'purchase_details_id' => null,
            ];
        }
    }

    public function batchChangeHandler($cartId)
    {
        $batch = PurchaseDetails::find($this->cart[$cartId]['batch_id']);
        if ($batch){
            $this->cart[$cartId]['sale_price'] = $this->cart[$cartId]['quantity'] * $batch->sale_price;
            $this->cart[$cartId]['batch_name'] = $batch->batch;
            $this->cart[$cartId]['purchase_details_id'] = $batch->id;
            $this->cart[$cartId]['expire_date'] = $batch->expire_date;
        }
    }

    public function removeFromCart($cartId)
    {
        unset($this->cart[$cartId]);
    }


    public function clearLastInvoice()
    {
        Session::forget('lastInvoiceId');
    }
    public function render()
    {
        return view('livewire.p-o-s',[
            'subtotal' => $this->calculateSubtotal(),
            'tax_amount' => $this->calculateTax(),
        ]);
    }

    public function calculateTax()
    {
        $totalVat = 0;
        foreach ($this->cart as $cart){
            $totalVat += calculatePercentage($cart['tax'],$cart['tax_value_type']);
        }
        return $totalVat;
    }

    public function calculateSubtotal()
    {
        $totalAmount = 0;
        foreach ($this->cart as $cart){
            $totalAmount += $cart['sale_price'] * $cart['quantity'];
        }
        return $totalAmount;
    }
}
