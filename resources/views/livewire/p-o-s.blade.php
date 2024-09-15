<div class="row">
    <div class="col-lg-7 p-3 position-relative pos-product">
        <div class="row">
            <div class="col-lg-9">
                <form action="" class="product-search gap-2  d-flex justify-content-between align-content-center">
                    <div class="search-input position-relative w-100">
                                <span class="append position-absolute top-0 start-0 ps-3 pt-2"><i
                                        class="bi bi-search"></i></span>
                        <input type="text" wire:model="search_keywords" wire:keyup="getProducts"
                               class="form-control form-control-lg ps-5 border-0 shadow-sm py-2 rounded-3"
                               placeholder="Search by product name">
                    </div>
                    <button class="btn btn-primary shadow-sm rounded-3 d-flex align-items-center gap-1">
                        <span>{{ translator('Search') }}</span>
                        <span wire:loading wire:target="getProducts()" class="spinner-border spinner-border-sm"
                              role="status">
                            <span class="visually-hidden">Loading...</span>
                        </span>
                    </button>
                </form>
            </div>
            <div class="col-lg-3 justify-content-end d-flex gap-4">
                <a href="{{ route('dashboard') }}"
                   class="btn btn-light bg-white text-primary shadow-sm rounded-3 border-0 position-relative">
                    <i class="bi bi-house"></i>
                </a>
                <button type="button" data-bs-toggle="modal" data-bs-target="#calculator"
                        class="btn btn-light bg-white text-primary shadow-sm rounded-3 border-0 position-relative">
                    <i class="bi bi-calculator"></i>
                </button>
                <div class="btn-group">
                    <button class="btn btn-light bg-white text-primary shadow-sm rounded-3 border-0"
                            data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu rounded-3 p-3">
                        <li><a class="dropdown-item rounded-3"
                               href="{{ route('sale.index') }}">{{ translator('Sale List') }}</a></li>
                        <li><a class="dropdown-item rounded-3"
                               href="{{ route('purchase.create') }}">{{ translator('Add Purchase') }}</a></li>
                        <li><a class="dropdown-item rounded-3"
                               href="{{ route('gateway.create') }}">{{ translator('Add Payment Method') }}</a></li>
                        <li><a class="dropdown-item rounded-3"
                               href="{{ route('product.create') }}">{{ translator('Add Medicine') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="modal fade" id="calculator" tabindex="-1" aria-labelledby="calculator" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="calculator">{{ translator('Calculator') }}</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body py-5">
                            <h2>{{ translator('Upcomming...') }}</h2>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="category pt-4">
            <h6 class="fw-bold text-muted">
                #{{ translator('All Categories') }}
                @if($category_id)
                    <a href="javascript:" class="text-danger" wire:click="categoryChangeHandler()">
                        <i class="bi bi-x"></i><small>{{ translator('Clear') }}</small>
                    </a>
                @endif
            </h6>

            <ul class="list-unstyled d-flex gap-2">
                @foreach($categories as $category)
                    <li>
                        <a href="javascript:" wire:click="categoryChangeHandler({{ $category->id }})"
                           class="btn {{ $category->id == $category_id ? 'btn-primary' :'btn-primary' }}  btn-sm  border-0 rounded-3 shadow-sm">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="row product-list row-cols-4 row-cols-sm-4 row-cols-md-3 row-cols-lg-5 row-cols-xxl-6 ">
            @forelse($products as $product)
                <div class="col px-2">
                    <a href="javascript:" wire:click="ADDTOCART('{{ $product->id }}')" class="d-block">
                        <div class="card shadow-sm rounded-3 mb-2 bg-white position-relative">
                            <small class="badge bg-gradient bg-light text-dark position-absolute top-0 start-0 m-2">
                                {{ translator('Stock') }} : {{ $product->total_quantity }}
                            </small>
                            <div class="card-body text-center pb-0 pt-2 px-2">
                                <img src="{{ $product->image }}" height="100" width="100" class=" rounded-3" alt="">
                            </div>
                            <div class="card-footer p-2 border-0 text-center rounded-3">
                                <strong class="text-truncate text-dark">{{ $product->name }}</strong>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-lg-12">
                    <x-empty></x-empty>
                </div>
            @endforelse
        </div>
    </div>
    <div class="col-lg-5 px-0 cart-calculator shadow-sm">
        <form action="{{ route('sale.store') }}" method="post" class="h-100">
            @csrf
            <div class="card h-100">
                <div class="card-header border-0">
                    <div class="row  align-content-center">
                        <div class="col-lg-8">
                            <select name="customer_id" class="form-select form-control-lg border-0 shadow-sm rounded-3"
                                    id="">
                                <option value="">{{ translator('Walking Customer') }}</option>
                                @foreach($customers as $customer)
                                    <option
                                        {{ @old('customer_id') == $customer->id ? 'selected':'' }} value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#addCustomerModal"
                                    class="btn btn-outline-light text-truncate shadow-sm border-0 text-primary">
                                <i class="bi bi-person-add"></i> {{ translator('Add Customer') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="medicine table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <th>{{ translator('Medcine') }}</th>
                                <th>{{ translator('Batch') }}</th>
                                <th>{{ translator('Expire Date') }}</th>
                                <th>{{ translator('Quantity') }}</th>
                                <th>{{ translator('Price') }}</th>
                                <th>{{ translator('Total') }}</th>
                                <th></th>
                            </tr>

                            @foreach($cart as $productId => $medicine)
                                <tr wire:key="row-{{ $medicine['id'] }}">
                                    <td>
                                    <span class="medicine_name text-truncate" title="{{ $medicine['name'] }}">
                                        {{ $medicine['name'] }}
                                    </span>
                                        <input type="hidden" name="items[{{ $productId }}][product_id]"
                                               value="{{ $productId }}">
                                    </td>
                                    <td>
                                        <select class="table-input" required
                                                name="items[{{ $productId }}][purchase_details_id]"
                                                wire:model.lazy="cart.{{ $productId }}.batch_id"
                                                wire:change="batchChangeHandler('{{ $productId }}')">
                                            <option value="">{{ translator('Batch') }}</option>
                                            @foreach($medicine['batches'] as $batch)
                                                <option value="{{ $batch->id }}">{{ $batch->batch }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                    <span class="expire_date text-truncate">
                                        {{ $medicine['expire_date'] }}
                                    </span>
                                    </td>
                                    <td>
                                        <input
                                            class="table-input" min="1" step="any" type="number"
                                            wire:model.lazy="cart.{{ $productId }}.quantity"
                                            name="items[{{ $productId }}][quantity]"
                                        >
                                    </td>
                                    <td>
                                        <input
                                            class="table-input" min="1" step="any" type="number"
                                            wire:model.lazy="cart.{{ $productId }}.sale_price"
                                            name="items[{{ $productId }}][price]" value="{{ $medicine['sale_price'] }}"
                                        ></td>
                                    <td>{{ number_format($medicine['sale_price'] * $medicine['quantity'], 2) }}</td>
                                    <input type="hidden" name="items[{{ $productId }}][subtotal]"
                                           value="{{ $medicine['sale_price'] * $medicine['quantity'] }}">
                                    <input type="hidden" name="items[{{ $productId }}][total]"
                                           value="{{ $medicine['sale_price'] * $medicine['quantity'] }}">
                                    <td>
                                        <a href="javascript:" wire:click="removeFromCart({{$productId}})"
                                           class="text-danger"><i class="bi bi-x-circle-fill"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="card-footer border-0">
                    <div class="col-lg-12">
                        <div class="row align-items-center mb-2">
                            <div class="col-lg-8 text-end">
                                <strong class="m-0">{{ translator('Subtotal') }}:</strong>
                            </div>
                            <div class="col-lg-4 text-end">
                                <input class="form-control disabled" disabled type="text"
                                       value="{{ number_format($subtotal,2) }}">
                                <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                            </div>
                        </div>
                        <div class="row align-items-center mb-2">
                            <div class="col-lg-8 text-end ">
                                <strong class="m-0">{{ translator('Invoice Discount') }}:</strong>
                            </div>
                            <div class="col-lg-4 text-end">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <input type="text" name="discount" wire:model.lazy="discount_amount"
                                               class="form-control">
                                    </div>
                                    <div class="col-lg-7 ps-0">

                                        <select name="discount_value_type" wire:model.lazy="discount_value_type"
                                                class="form-select" id="">
                                            <option value="percent">%</option>
                                            <option value="fixed">{{ translator('Fixed') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center mb-2">
                            <div class="col-lg-8 text-end">
                                <strong class="m-0">{{ translator('Total Discount') }}:</strong>
                            </div>
                            @php
                                $total_discount_amount = calculateDiscountAmount($subtotal, $discount_amount, $discount_value_type);
                                $grand_total = ($subtotal - $total_discount_amount) + $tax_amount
                            @endphp
                            <div class="col-lg-4 text-end">
                                <input class="form-control disabled" disabled type="text"
                                       value="{{ number_format($total_discount_amount,2) }}">
                            </div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-lg-8 text-end">
                                <strong class="m-0">{{ translator('Tax on all product') }}:</strong>
                            </div>
                            <div class="col-lg-4 text-end">
                                <input class="form-control disabled" disabled type="text"
                                       value="{{ number_format($tax_amount,2) }}">
                                <input type="hidden" name="tax" value="{{ $tax_amount }}">
                            </div>
                        </div>
                        <div class="row align-items-center mb-2">
                            <div class="col-lg-8 text-end">
                                <strong class="m-0">{{ translator('Total') }}:</strong>
                            </div>
                            <div class="col-lg-4 text-end">
                                <input class="form-control disabled" disabled type="text"
                                       value="{{ number_format($grand_total,2) }}">
                                <input type="hidden" name="total" value="{{ $grand_total }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-4">
                                <select name="gateway_id" class="form-select form-control-lg rounded-3" id="">
                                    @foreach($gatewayes as $gateway)
                                        <option
                                            {{ @old('customer_id') == $gateway->id ? 'selected':'' }} value="{{ $gateway->id }}">{{ $gateway->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-8">
                                <button type="submit" class="btn btn-primary py-2 d-block w-100 rounded-3">
                                    {{ translator('Pay Now') }} {{ formatPrice($grand_total) }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <!-- Add new customer modal -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ translator('Add New Customer') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <x-form class="row" wire:submit.prevent="addNewCustomer" method="post">
                        <x-form.input
                            class="col-lg-6"
                            :browserRequired="true"
                            label="Name"
                            name="name"
                            wire:model="customer.name"
                        ></x-form.input>
                        <x-form.input
                            class="col-lg-6"
                            :browserRequired="true"
                            label="Email"
                            name="email"
                            wire:model="customer.email"
                        ></x-form.input>
                        <x-form.input
                            class="col-lg-6"
                            :browserRequired="true"
                            label="Phone"
                            name="phone"
                            wire:model="customer.phone"
                        ></x-form.input>
                        <x-form.input
                            class="col-lg-6"
                            type="number"
                            label="Due"
                            name="due"
                            value="0"
                            wire:model="customer.due"
                        ></x-form.input>
                        <x-form.radio
                            class="col-lg-12"
                            label="Status"
                            name="status"
                            :options="['Active', 'Inactive']"
                            checked="Active"
                            wire:model="customer.status"
                        ></x-form.radio>
                        <x-form.textarea label="Address" name="address" wire:model="customer.address"></x-form.textarea>
                        <x-form.button type="submit" position="end" label="Submit"></x-form.button>
                    </x-form>
                </div>
            </div>
        </div>
    </div>

@if(!empty($saleInvoice))
    <!-- Modal -->
        <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header border-0 justify-content-end pb-0">
                            <button wire:click="clearLastInvoice()" type="button" class="btn-close text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    <div class="modal-body p-0 pb-3">
                        <div class="">
                            <div class="invoice" id="purchaseInvoice">
                                <table class="table header border-0 mb-0">
                                    <tr>
                                        <td class="text-center border-0">
                                            <h4><b>{{ setting('app_name') }}</b></h4>
                                            <p>{{ setting('address') }}</p>
                                            <p>{{ translator('Phone Number') }}: {{ setting('phone') }}</p>
                                            <h5 class="invoice-title">{{ translator('Sale Invoice') }}</h5>
                                        </td>
                                    </tr>
                                </table>
                                <span class="table__devider"></span>
                                <table class="table table-borderless border-0">
                                    <tr>
                                        <th>{{ translator('Invoice Number') }}:</th>
                                        <td>{{ $saleInvoice->invoice_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ translator('Sale Date') }}:</th>
                                        <td>{{ date('F d, Y', strtotime($saleInvoice->sale_date)) }} {{ date('h:i A', strtotime($saleInvoice->created_at)) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ translator('Sold To') }}:</th>
                                        <td> @if($saleInvoice->customer)
                                                {{ @$saleInvoice->customer->name }} @else
                                                <p>{{ translator('Walking Customer') }}</p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ translator('Sales Person') }}:</th>
                                        <td>{{ auth()->user()->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ translator('Status') }}:</th>
                                        <td>
                                            <span
                                                class="text-capitalize badge bg-{{ $saleInvoice->status == 'sold' ?'success':'danger' }}">{{ $saleInvoice->status }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ translator('Payment Method') }}:</th>
                                        <td>{{ @$saleInvoice->gateway->name }}</td>
                                    </tr>
                                </table>
                                <span class="table__devider"></span>
                                <table class="table item-details-table">
                                    <tr>
                                        <th>{{ translator('Medicine') }}</th>
                                        <th>{{ translator('Price') }}</th>
                                        <th>{{ translator('Qty') }}</th>
                                        <th>{{ translator('Subtotal') }}</th>
                                        <th>{{ translator('Discount') }}</th>
                                        <th class="text-end">{{ translator('Total') }}</th>
                                    </tr>

                                    @forelse ($saleInvoice->sale_details ?? [] as $product)
                                        <tr>
                                            <td>{{ @$product->product->name }}</td>
                                            <td>{{ formatPrice($product->price) }}</td>
                                            <td class="text-center">{{ $product->quantity }}</td>
                                            <td>{{ formatPrice($product->subtotal) }}</td>
                                            <td class="text-center">{{ formatPrice($product->discount) }}</td>
                                            <td class="text-end">{{ formatPrice($product->total) }}</td>
                                            @empty
                                                <td colspan="6">
                                                    <h4>{{ translator('No data available') }}</h4>
                                                </td>
                                        </tr>
                                    @endforelse
                                </table>
                                <span class="table__devider"></span>
                                <table class="table mb-0">
                                    <tr>
                                        <td class="text-end border-0">
                                            <table class="table table-borderless estimate-table">
                                                <tr>
                                                    <th>{{ translator('Subtotal') }}:</th>
                                                    <td>{{ formatPrice($saleInvoice->subtotal) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ translator('Discount') }}:</th>
                                                    <td>{{ formatPrice($saleInvoice->discount) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ translator('Tax') }}:</th>
                                                    <td>{{ formatPrice($saleInvoice->tax) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ translator('Total Amount') }}:</th>
                                                    <td>{{ formatPrice($saleInvoice->total) }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <span class="table__devider"></span>
                                <table class="table border-0">
                                    <tr>
                                        <th class="text-center border-0">Thank you for choosing us</th>
                                    </tr>
                                    <tr >
                                        <th class="text-center border-0">{{ config('app.name') }}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="javascript:" id="window-printer" onclick="return(window.print())" class="btn btn-primary"><i
                                    class="bi bi-printer"></i> {{translator('Print Invoice')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@if(!empty($saleInvoice))
    <script>
        window.onload = function () {
            let invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
            invoiceModal.show();
        };
    </script>
@endif

