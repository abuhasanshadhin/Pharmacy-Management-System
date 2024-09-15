<div class="">
    <div class="row">
        <div class="col-lg-4 mb-2">
            <div class="row align-items-center">
                <label for="" class="col-lg-2 form-label mb-0">{{ translator('Ref') }}</label>
                <div class="col-lg-10">
                    <input
                        type="text"
                        name="reference"
                        placeholder="Write reference..."
                        class="form-control"
                    >
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-2">
            <div class="row align-items-center">
                <label for="" class="col-lg-2 form-label mb-0">{{ translator('Date') }}</label>
                <div class="col-lg-10">
                    <input
                        type="date"
                        name="purchase_date"
                        class="form-control"
                        wire:model="purchase_date"
                    >
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-2">
            <div class="row align-items-center">
                <label for="" class="col-lg-3 form-label mb-0">{{ translator('Supplier') }}</label>
                <div class="col-lg-9">
                    <select name="supplier_id" id="" class="form-select">
                        <option value="">―{{ translator('Choose') }}―</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-2">
        <div class="row" >
            <div class="col-lg-3">
                <strong>{{ translator('Medicine In your Cart') }}</strong>
                <span class="btn btn-sm btn-white px-3 text-primary shadow-sm">
                    <span> {{ count($carts) }} </span>
                </span>
            </div>
            <div class="col-lg-7 mb-3 position-relative">
                <input type="text" class="form-control ps-5"
                       id="searchInputField"
                       wire:model="keyword"
                       wire:keyup="getProduct()"
                       wire:focus="visibleProductList()"
                       placeholder="Search by name, barcode and sku"
                       autocomplete="off"
                >
                <span class="position-absolute top-0 start-0 p-2 ps-4">
                    <i class="bi bi-search"></i>
                </span>
                @if($is_visibleProductList)
                    <div class="product-search-result">
                        <div class="d-flex justify-content-between">
                            <strong class="fw-bold text-muted">
                                <span wire:loading.remove>{{ translator('Medicine Found') }} ({{ count($products) }})</span>
                                <span wire:loading wire:target="getProduct()">
                                <div class="spinner-border spinner-border-sm" role="status">
                                  <span class="visually-hidden">Loading...</span>
                                </div> {{ translator('Searching') }}...
                            </span>
                            </strong>
                            <a href="javascript:" title="Close" wire:click="hideSearchList()"
                               class="me-3 text-danger"><i
                                    class="bi bi-x-lg"></i></a>
                        </div>
                        <ul class="list-unstyled mt-3" id="medicineList">
                            @include('purchase.medicine')
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-lg-2">
                <button type="button" wire:click="getProduct()" class="btn btn-sm btn-primary">
                    <i class="bi bi-search"></i> {{ translator('Search') }}
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <div id="purchase_cart">
                <table class="table purchase-table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>{{ translator('Medicine') }}</th>
                        <th>{{ translator('Batch') }}</th>
                        <th>{{ translator('Expire Date') }}</th>
                        <th>{{ translator('Purchase Price') }}</th>
                        <th>{{ translator('Sale Price') }}</th>
                        <th>{{ translator('Quantity') }}</th>
                        <th>{{ translator('Subtotal') }}</th>
                        <th>{{ translator('Discount') }}</th>
                        <th class="text-center">{{ translator('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($carts as $productId => $cart)
                        @php
                            $subtotal = $cart['purchase_price'] * $cart['quantity'];
                            $discount_amount = calculateDiscountAmount($subtotal, $cart['discount'], $cart['discount_type']);
                            $total = $subtotal - $discount_amount;
                        @endphp
                        <tr wire:key="row-{{ $cart['product_id'] }}">
                            <th>{{ $loop->iteration }}</th>
                            <td>
                                <input type="hidden" name="purchase_items[{{ $productId }}][product_id]" value="{{ $cart['product_id'] }}">
                                <img src="{{ $cart['image'] }}" height="40" width="40" alt="">
                                {{ $cart['name'] }}
                            </td>
                            <td>
                                <input
                                    required
                                    type="text"
                                    wire:model="carts.{{ $productId }}.batch"
                                    name="purchase_items[{{ $productId }}][batch]"
                                    class="table-input">
                            </td>
                            <td>
                                <input
                                    required
                                    type="date"
                                    wire:model="carts.{{ $productId }}.expire_date"
                                    name="purchase_items[{{ $productId }}][expire_date]"
                                    class="input-width-120">
                            </td>
                            <td>
                                <input
                                    required
                                    wire:model.lazy="carts.{{ $productId }}.purchase_price"
                                    value="{{ $cart['purchase_price'] }}"
                                    name="purchase_items[{{ $productId }}][purchase_price]"
                                    class="table-input"
                                    type="number">
                            </td>
                            <td>
                                <input
                                    required
                                    wire:model.lazy="carts.{{ $productId }}.sale_price"
                                    type="number"
                                    value="{{ $cart['sale_price'] }}"
                                    name="purchase_items[{{ $productId }}][sale_price]"
                                    class="table-input"
                                >
                            </td>
                            <td>
                                <input
                                    required
                                    wire:model.lazy="carts.{{ $productId }}.quantity"
                                    min="1"
                                    type="number"
                                    name="purchase_items[{{ $productId }}][quantity]"
                                    class="table-input"
                                    value="{{ $cart['quantity'] }}"
                                >
                            </td>
                            <td>
                                <input type="hidden" name="purchase_items[{{ $productId }}][subtotal]" value="{{ $subtotal }}">
                                {{ formatPrice($subtotal) }}
                            </td>
                            <td>
                                <input
                                    wire:model.lazy="carts.{{ $productId }}.discount"
                                    type="number"
                                    name="purchase_items[{{ $productId }}][discount]"
                                    class="table-input"
                                >
                                <select name="purchase_items[{{ $productId }}][discount_value_type]" id="" class="table-select" wire:model.lazy="carts.{{ $productId }}.discount_type">
                                    <option value="percent">%</option>
                                    <option value="fixed">{{ translator('Fixed') }}</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="purchase_items[{{ $productId }}][total]" value="{{ $total }}">
                                {{ formatPrice($total) }}
                            </td>
                            <td class="text-center">
                                <a href="javascript:" wire:click="removeCartItem({{ $productId }})" class="text-danger">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">
                                <h5 class="py-3 text-muted">{{ translator('Your cart is empty') }} ):</h5>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="row mt-3">
            <div class="col-md-7">
                <div class="row mb-3">
                    <label for="note" class="col-md-3">{{ translator('Note') }}</label>
                    <div class="col-md-9">
                        <textarea id="note" name="note" class="form-control form-control-sm"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="" class="col-md-3">{{ translator('Status') }}<span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <div>
                            <div class="form-check-sm form-check-inline form-check">
                                <input type="radio" name="status" id="check-0"
                                       class="form-check-input"
                                       checked
                                       value="received">
                                <label for="check-0" class="form-check-label">{{ translator('Received') }}</label>
                            </div>
                            <div class="form-check-sm form-check-inline form-check">
                                <input type="radio" name="status" id="check-1"
                                       class="form-check-input"
                                       value="pending">
                                <label for="check-1" class="form-check-label">{{ translator('Pending') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 clearfix">
                <div class="float-end">
                    <table class="table table-borderless purchase-table">
                        <tbody>
                        <tr>
                            <td class="fw-bold">{{ translator('Subtotal') }}</td>
                            <td>:</td>
                            <td>
                                {{ formatPrice($subtotalAmount)  }}
                                <input type="hidden" name="subtotal" value="{{ $subtotalAmount }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">{{ translator('Medicine\'s Discount') }}</td>
                            <td>:</td>
                            <td>
                                {{ formatPrice($medicineDiscountAmount) }}
                                <input type="hidden" name="medicine_discount_amount" value="{{ $medicineDiscountAmount }}" class="number disabled" step="any" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">{{ translator('Invoice Discount') }}</td>
                            <td>:</td>
                            <td>
                                <div class="d-flex">
                                    <input type="number" wire:model.lazy="discount" name="discount" class="number" step="any">
                                    <select class="type-select" wire:model.lazy="discount_value_type" name="discount_value_type">
                                        <option value="percent">%</option>
                                        <option value="fixed">{{ translator('Fixed') }}</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">{{ translator('Total Discount') }}</td>
                            <td>:</td>
                            <td>
                            @php
                                $invoiceDiscount = calculateDiscountAmount($subtotalAmount,$discount,$discount_value_type);
                                $totalDiscountAmount = $medicineDiscountAmount + $invoiceDiscount
                            @endphp
                                {{ formatPrice($totalDiscountAmount) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">{{ translator('Tax') }}</td>
                            <td>:</td>
                            <td>
                                <div class="d-flex">
                                    <input type="number" wire:model.lazy="tax" name="tax" class="number" step="any">
                                    <select class="type-select" wire:model.lazy="tax_value_type" name="tax_value_type">
                                        <option value="percent">%</option>
                                        <option value="fixed">{{ translator('Fixed') }}</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">{{ translator('Tax Amount') }}</td>
                            <td>:</td>
                            <td>
                                @php
                                    $totalTaxAmount = calculateDiscountAmount($subtotalAmount,$tax,$tax_value_type);
                                @endphp
                                {{ formatPrice($totalTaxAmount) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">{{ translator('Grand Total') }}</td>
                            <td>:</td>
                            @php
                                $grandTotal = ($subtotalAmount - $totalDiscountAmount) + $totalTaxAmount;
                            @endphp
                            <td>
                                <input type="hidden" name="grand_total" value="{{ $grandTotal }}">
                                {{ formatPrice($grandTotal) }}
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translator('Payment Method') }}</th>
                            <td>:</td>
                            <td>
                                <select name="gateway_id" class="form-select form-control-lg rounded-3" id="">
                                    @foreach($gatewayes as $gateway)
                                        <option
                                            {{ @old('customer_id') == $gateway->id ? 'selected':'' }} value="{{ $gateway->id }}">{{ $gateway->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary d-block w-100">{{ translator('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
