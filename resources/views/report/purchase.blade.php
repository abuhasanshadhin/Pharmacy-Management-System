@extends('layout.app')
@php
    $breadcrumbs = [
        ['text' => 'Report'],
        ['text' => 'Purchase Report']
    ];
@endphp
@section('content')
    <x-container
        title="Purchase Report"
        :breadcrumb="$breadcrumbs"
        :button="false"
    >
        <form>
            <div class="row gx-2">
                <x-form.select name="supplier_id" class="col-lg-3" label="Supplier">
                    @foreach($suppliers as $supplier)
                        <option {{ request('supplier_id') == $supplier->id ? 'selected' :'' }} value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </x-form.select>
                <x-form.input
                    class="col-lg-2"
                    name="from_date"
                    value="{{ request('from_date') }}"
                    label="From Date"
                    type="date">
                </x-form.input>
                <x-form.input
                    class="col-lg-2"
                    name="to_date"
                    value="{{ request('to_date') }}"
                    label="To Date"
                    type="date">
                </x-form.input>
                <div class="col-md-4 mb-2">
                    <label for="" class="form-label mb-0">{{ translator('Search') }}</label>
                    <div class="row g-1 justify-content-center">
                        <div class="col-md-4">
                            <select name="column" class="form-control form-control-sm">
                                <option value="invoice_number">{{ translator('Invoice no') }}.</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="search_value" placeholder="Type something..."
                                   class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
                <div class="col-md-1 mb-2 mt-4">
                    @if(request('supplier_id') || request('from_date') || request('to_date') || request('column') || request('search_value'))
                        <a href="{{ route('report.purchases') }}"  class="btn btn-sm btn-danger border"><i class="bi bi-x"></i></a>
                        <button type="submit" class="btn btn-sm btn-light border"><i class="bi bi-search"></i></button>
                    @else
                        <button type="submit" class="btn btn-sm btn-light border"><i class="bi bi-search"></i></button>
                    @endif
                </div>
            </div>
        </form>
        <div class="mt-2">
            <div id="printArea"><h4 class="mb-0 text-center">{{ translator('Purchase Report') }}</h4>
                <div class="text-center">
                    @if(isset($from_date))
                        {{ date('d/m/Y', strtotime($from_date)) }}
                    @endif
                    <span class="fw-bold mx-3">-</span>
                    @if(isset($to_date))
                        {{ date('d/m/Y', strtotime($to_date)) }}
                    @endif
                </div>
                <table class="table table-bordered table-sm text-center mb-0 mt-3">
                    <thead>
                    <tr>
                        <th class="align-middle" rowspan="2">{{ translator('Invoice No') }}.</th>
                        <th class="align-middle" rowspan="2">{{ translator('Date') }}</th>
                        <th class="align-middle" colspan="2">{{ translator('Supplier') }}</th>
                        <th class="align-middle" rowspan="2">{{ translator('Subtotal') }}</th>
                        <th class="align-middle" rowspan="2">{{ translator('Tax') }}</th>
                        <th class="align-middle" rowspan="2">{{ translator('Discount') }}</th>
                        <th class="align-middle" rowspan="2">{{ translator('Total') }}</th>
                    </tr>
                    <tr>
                        <th>{{ translator('Name') }}</th>
                        <th>{{ translator('Phone') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($purchases ?? [] as $purchase)
                        <tr>
                            <td>{{ $purchase->invoice_no }}</td>
                            <td>{{ $purchase->purchase_date }}</td>
                            <td>{{ $purchase->supplier_name }}</td>
                            <td>{{ $purchase->supplier_phone }}</td>
                            <td>{{ formatPrice($purchase->subtotal) }}</td>
                            <td>{{ formatPrice($purchase->tax) }}</td>
                            <td>{{ formatPrice($purchase->discount) }}</td>
                            <td>{{ formatPrice($purchase->grand_total) }}</td>
                        </tr>
                    @empty
                        <x-not_found colspan="7"></x-not_found>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
