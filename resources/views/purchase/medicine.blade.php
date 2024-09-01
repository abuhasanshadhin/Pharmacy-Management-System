@foreach($products as $product)
    <li class="d-flex justify-content-between align-content-center border-bottom pb-1 mb-2">
        <div class="d-flex gap-2">
            <img src="{{ $product['image'] }}" height="40" width="40" alt="">
            <div class="d-flex flex-column gap-1">
                <strong>{{ $product['name'] }}</strong>
                <small class="text-muted">{{ translator('SKU') }}: {{ $product['sku'] }}</small>
            </div>
        </div>
        <a href="javascript:" wire:click="ADDTOCART({{ $product['id'] }})" title="Add To Cart" class="btn btn-primary btn-sm rounded-circle">
            <i class="bi bi-plus"></i>
        </a>
    </li>
@endforeach
