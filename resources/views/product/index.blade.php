@extends('layout.app')
@php
    $breadcrumbs = [['text' => 'Medicines'], ['text' => 'Medicines List']];
    $headers = [
            '#','Medicine', 'Supplier','Category', 'Unit','Purchase Price','Sale Price','Status', 'Action'
     ];
@endphp
@section('content')
    <x-container
        title="Medicines"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="product.create"
        btn_title="Add New"
    >
        <div class="clearfix">
            <div class="float-start">
                <select name="limit" class="form-select mb-3">
                    <option value="10">{{ translator('10') }}</option>
                    <option value="20">{{ translator('20') }}</option>
                    <option value="50">{{ translator('50') }}</option>
                    <option value="100">{{ translator('100') }}</option>
                    <option value="500">{{ translator('500') }}</option>
                    <option value="-1">{{ translator('All') }}</option>
                </select>
            </div>
            <div class="float-right">
                <form action="" method="GET" class="row">
                    <div class="col-2 pe-0">
                        <select name="category_id" class="form-select">
                            <option selected value="">{{ translator('-Category-') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if($category->id == request('category_id')) selected @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2 pe-0">
                        <select name="supplier_id" class="form-select">
                            <option selected value="">{{ translator('-Manufaturer-') }}</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                        @if($supplier->id == request('supplier_id')) selected @endif>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2 pe-0">
                        <select name="unit_id" class="form-select">
                            <option selected value="">{{ translator('-Unit-') }}</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" @if($unit->id == request('unit_id')) selected @endif>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-5">
                        <div class="row">
                            <div class="col-5 pe-0">
                                <select name="field" class="form-select">
                                    <option value="barcode">{{ translator('Barcode') }}</option>
                                    <option value="name">{{ translator('Medicine') }}</option>
                                    <option value="purchase_price">{{ translator('Purchase Price') }}</option>
                                    <option value="sale_price">{{ translator('Sale Price') }}</option>
                                    <option value="status">{{ translator('Status') }}</option>
                                </select>
                            </div>
                            <div class="col-7 pe-0 position-relative">
                                <input type="text" name="keyword" class="form-control"
                                       value="{{ request('keyword') }}">
                                @if(request('category_id') && request('supplier_id') && request('unit_id') || request('keyword'))
                                    <div class="position-absolute top-0 end-0 p-2" title="Clear Search">
                                        <a href="{{ route('product.index') }}" class="text-danger">
                                            <i class="bi bi-x-lg"></i></a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-1 pe-0">
                        <button type="submit" class="btn btn-dark"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="bg-custom">
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <img height="60" width="60" src="{{$product->image}}" alt="">
                                <div class="d-flex flex-column">
                                    <small><b>{{$product->name }}</b></small>
                                    <small class="text-muted text-width-200" title="{{ $product->sku }}">
                                        <b>{{ translator('SKU') }}</b>: {{ $product->sku }}
                                    </small>
                                    <small class="text-muted text-width-200" title="{{ $product->generaic_name }}">
                                        <b>{{ translator('Gen') }}</b>: {{ $product->generic_name }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-width-100" title="{{ $product->supplier->name }}">
                                {{ $product->supplier->name }}
                            </span>
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->unit->name }}</td>
                        <td class="text-center">{{ $product->purchase_price }}</td>
                        <td class="text-center">{{ $product->sale_price }}</td>
                        <td>
                    <span class="badge bg-{{$product->status=='active'?'success':'danger'}}">
                        {{ $product->status }}
                    </span>
                        </td>
                        <td>
                            @can('product.show')
                                <a href="{{ route('product.show', $product->id) }}"
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @endcan
                            @can('product.edit')
                                <a href="{{ route('product.edit', $product->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endcan
                            @can('product.destroy')
                                <form onsubmit="return confirm('Are you sure?')"
                                      action="{{ route('product.destroy', $product->id) }}" class="d-inline"
                                      method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) }}" class="text-center">
                            <h4 class="py-5 text-muted">{{ translator('No data found') }} ):</h4>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {!! $products->links() !!}
    </x-container>
@endsection
@push('scripts')
    <script>
        var limitSelects = document.querySelectorAll('select[name="limit"]');
        var totalLimitSelects = limitSelects.length;

        for (let i = 0; i < totalLimitSelects; i++) {
            var element = limitSelects[i];
            element.addEventListener('change', function (ev) {
                var url = new URL('', "{{ route('product.index') }}");
                url.searchParams.set('limit', ev.target.value);
                window.location.href = url.toString();
            });
        }
    </script>
@endpush
