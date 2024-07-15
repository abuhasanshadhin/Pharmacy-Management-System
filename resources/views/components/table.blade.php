@props([
'headers' => [],
'collection' => [],
'actions' => [],
'search_action' => '',
'limits' => [10, 20, 50, 100, 500, -1],
'defaultSearch' => true
])

@php
    $hasActions = is_array($actions) && count($actions);
    $hasPagination = $collection instanceof Illuminate\Pagination\LengthAwarePaginator;
@endphp

<div class="row mb-3 justify-content-between">
    <div class="col-3">
        @if ($hasPagination)
            <div class="col-4">
                <select
                    name="limit"
                    class="form-select"
                >
                    @foreach ($limits as $limit)
                        @if ($limit == -1)
                            <option value="-1">{{ translator('All') }}</option>
                        @else
                            <option {{ request('limit') == $limit ? 'selected' : ''}} value="{{ $limit }}">{{ $limit }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        @endif
    </div>
    <div class="col-9">
        <form
            action="{{ $search_action }}"
            method="GET"
        >
            @if($defaultSearch)
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-3">
                            <select name="field" class="form-select">
                                @foreach($headers as $header)
                                    @if(array_key_exists('searchable',$header) && $header['searchable'])
                                        <option value="{{ $header['value'] }}" @if($header['value'] == request('field')) selected @endif>
                                            {{ $header['text'] }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-8">
                            <input type="search" name="keyword" class="form-control" value="{{ request('keyword') }}">
                        </div>
                        <div class="col-1">
                            <button type="submit" class="btn btn-table-search"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </div>
            @else
                {{ $searchForm ?? null }}
            @endif
        </form>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover">
        <thead class="bg-custom">
        <tr>
            <th>#</th>
            @foreach ($headers as $header)
                <th>
                    <span class="text-width-125" title="{{ $header['text'] ?? null }}">
                        {{ translator($header['text']) ?? null }}
                    </span>
                </th>
            @endforeach
            @if ($hasActions)
                <th>{{ translator('Action') }}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse($collection as $item)
            <tr>
                <td>
                    {{ $hasPagination ? $collection->firstItem() + $loop->index : $loop->iteration }}
                </td>
                @foreach ($headers as $header)
                    @php
                        $field = CH::tableCellText($item, $header);
                    @endphp
                    <td>
                        @if($field == 'active')
                            <span class="badge bg-success">
                                {{$field}}
                            </span>
                        @elseif($field == 'inactive')
                            <span class="badge bg-danger">
                                {{$field}}
                            </span>
                        @else
                            <span class="text-width-125" title="{{ $field }}">
                                {{ $field }}
                            </span>
                        @endif
                    </td>
                @endforeach
                @if ($hasActions)
                    <td>
                        @foreach ($actions as $action)
                            @if (isset($action['method']) && strtolower($action['method']) == 'delete')
                                <form
                                    action="{{ CH::tableActionLink($item, $action) }}"
                                    {!! CH::htmlTagAttrsArrayToString($action['attrs'] ?? []) !!}
                                    onclick="return confirm('Are you sure?')"
                                    class="d-inline-block"
                                    method="POST"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        @class([
                                    'btn', 'btn-sm', $action['class'] ?? 'btn-outline-danger'])
                                    >
                                    @if(isset($action['icon']))
                                    <span></span>
                                    <i class="{{ $action['icon'] ?? null }}"></i>
                                    @else
                                        <i icon-data="delete"></i>
                                    @endif
                                    {{ $action['text'] ?? null }}
                                    </button>
                                </form>
                            @else
                                @if(!empty($action))
                                <a
                                    id="{{ $action['id'] ?? null }}"
                                    href="{{ CH::tableActionLink($item, $action) }}"
                                    @class(['btn', 'btn-sm', $action['class'] ?? 'btn-outline-light'])
                                {!! CH::htmlTagAttrsArrayToString($action['attrs'] ?? []) !!}
                                >
                                <i class="{{ $action['icon'] ?? null }}"></i>
                                {{ $action['text'] ?? null }}
                                </a>
                                @endif
                            @endif
                        @endforeach
                    </td>
                @endif
            </tr>
        @empty
            <x-not_found colspan="{{ count($headers) + 2 }}"></x-not_found>
        @endforelse
        </tbody>
    </table>
</div>

@if ($hasPagination)
    {{ $collection->links() }}

    @pushOnce('scripts')
    <script>
        var limitSelects = document.querySelectorAll('select[name="limit"]');
        var totalLimitSelects = limitSelects.length;

        for (let i = 0; i < totalLimitSelects; i++) {
            var element = limitSelects[i];
            element.addEventListener('change', function (ev) {
                var url = new URL('', "{{ $collection->path() }}");
                url.searchParams.set('limit', ev.target.value);
                window.location.href = url.toString();
            });
        }
    </script>
    @endPushOnce
@endif
