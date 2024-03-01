<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Stock;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class StockService
{
    /**
     * Update products stock on purchase
     *
     * @param array $new_items
     * @param Collection $prev_items
     * @return void
     */
    public function updateOnPurchase($new_items, $prev_items = null)
    {
        $new_stock_items = [];

        $products_id = array_column($new_items, 'product_id');

        if (is_object($prev_items)) {
            $prev_items_id = $prev_items->pluck('product_id', 'product_id')->toArray();
            $products_id = collect($products_id)->merge($prev_items_id)->unique()->toArray();
        }

        $stock = $this->byProduct($products_id);

        foreach ($new_items as $new_item) {
            $product_id = $new_item['product_id'];
            $new_qty = $new_item['quantity'];
            $item_stock = $stock->where('product_id', $product_id)->first();

            if (is_object($item_stock)) {
                if (is_object($prev_items)) {
                    $prev_item = $prev_items->where('product_id', $product_id)->first();

                    if (is_object($prev_item)) {
                        $prev_qty = $prev_item['quantity'];

                        if ($new_qty > $prev_qty) {
                            $item_stock->quantity += $new_qty - $prev_qty;
                            $item_stock->save();
                        } else if ($new_qty < $prev_qty) {
                            if (($prev_qty - $new_qty) > $item_stock->quantity) {
                                $this->insufficientStockException($product_id);
                            }

                            $item_stock->quantity -= $prev_qty - $new_qty;
                            $item_stock->save();
                        }

                        if (isset($prev_items_id[$product_id])) {
                            unset($prev_items_id[$product_id]);
                        }
                    } else {
                        $item_stock->quantity += $new_qty;
                        $item_stock->save();
                    }
                } else {
                    $item_stock->quantity += $new_qty;
                    $item_stock->save();
                }
            } else {
                $new_stock_items[] = [
                    'product_id' => $product_id,
                    'quantity' => $new_qty,
                    'created_at' => now(),
                ];
            }
        }

        if (is_object($prev_items) && isset($prev_items_id) && count($prev_items_id) > 0) {
            foreach ($prev_items_id as $id) {
                $item_stock = $stock->where('product_id', $id)->first();
                $prev_item = $prev_items->where('product_id', $id)->first();

                if (is_object($item_stock) && is_object($prev_item)) {
                    if ($prev_item->quantity > $item_stock->quantity) {
                        $this->insufficientStockException($id);
                    }

                    $item_stock->quantity -= $prev_item->quantity;
                    $item_stock->save();
                }
            }
        }

        if (count($new_stock_items) > 0) {
            Stock::insert($new_stock_items);
        }
    }

    /**
     * Update products stock on sale
     *
     * @param array $new_items
     * @param Collection $prev_items
     * @return void
     */
    public function updateOnSale($new_items, $prev_items = null)
    {
        if (is_object($prev_items)) {
            $new_items_id = array_column($new_items, 'product_id');
            $prev_items_id = $prev_items->pluck('product_id', 'product_id')->toArray();
            $products_id = collect($new_items_id)->merge($prev_items_id)->unique()->toArray();

            $stock = $this->byProduct($products_id);

            foreach ($new_items as $new_item) {
                $product_id = $new_item['product_id'];
                $new_qty = $new_item['quantity'];
                $item_stock = $stock->where('product_id', $product_id)->first();

                if (is_object($item_stock)) {
                    $prev_item = $prev_items->where('product_id', $product_id)->first();

                    if (is_object($prev_item)) {
                        $prev_qty = $prev_item['quantity'];
                        unset($prev_items_id[$product_id]);

                        if ($prev_qty > $new_qty) {
                            $item_stock->quantity += $prev_qty - $new_qty;
                            $item_stock->save();
                        } else if ($prev_qty < $new_qty) {
                            if ($new_qty > ($item_stock->quantity + $prev_item->quantity)) {
                                $this->insufficientStockException($product_id);
                            }

                            $item_stock->quantity -= $new_qty - $prev_qty;
                            $item_stock->save();
                        }
                    } else {
                        if ($new_qty > $item_stock->quantity) {
                            $this->insufficientStockException($product_id);
                        }

                        $item_stock->quantity -= $new_qty;
                        $item_stock->save();
                    }
                }
            }

            foreach ($prev_items_id as $id) {
                $item_stock = $stock->where('product_id', $id)->first();
                $prev_item = $prev_items->where('product_id', $id)->first();

                if (is_object($item_stock) && is_object($prev_item)) {
                    $item_stock->quantity += $prev_item->quantity;
                    $item_stock->save();
                }
            }
        } else {
            $this->updateQuantity(
                $new_items,
                'decrement',
                function ($item, $stock) {
                    if ($item['quantity'] > $stock->quantity) {
                        $this->insufficientStockException($item['product_id']);
                    }
                }
            );
        }
    }

    /**
     * Throw an exception when the product is not in enough stock
     *
     * @param int $product_id
     * @return void
     */
    public function insufficientStockException($product_id)
    {
        $products = collect(Cache::get(Product::class));
        $product = $products->where('id', $product_id)->first();
        $product_name = $product['name'] ?? '';

        throw new Exception("{$product_name} is not in enough stock.", 422);
    }

    /**
     * Update products stock quantity
     *
     * @param array $items
     * @param string $type
     * @param callable $callback
     * @return void
     */
    public function updateQuantity($items, $type = 'increment', $callback = null)
    {
        $items = $items instanceof Collection ? $items->toArray() : $items;
        $products_id = array_column($items, 'product_id');
        $stock = $this->byProduct($products_id);
        foreach ($items as $item) {
            $item_stock = $stock->where('product_id', $item['product_id'])->first();

            if (is_object($item_stock)) {
                if (is_callable($callback)) {
                    $callback($item, $item_stock);
                }

                switch ($type) {
                    case 'increment':
                        $item_stock->quantity += $item['quantity'];
                        $item_stock->save();
                        break;
                    case 'decrement':
                        $item_stock->quantity -= $item['quantity'];
                        $item_stock->save();
                        break;
                }
            }
        }
    }

    /**
     * Specific product stock
     *
     * @param array|int $id
     * @return object|array|null
     */
    public function byProduct($id)
    {
        $query = Stock::select('id', 'product_id', 'quantity');

        if (is_array($id)) {
            return $query->whereIn('product_id', $id)->get();
        }

        return $query->where('product_id', $id)->first();
    }
}
