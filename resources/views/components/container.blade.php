@props([
'title' => 'Page Title',
'breadcrumb' => [],
'url' => '',
'btn_title' => '',
'button' => false,
])

<div class="pagetitle">
    <div class="clearfix">
        <div class="float-start">
            <h4>{{ $title }}</h4>
            <nav>
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="/">{{ translator('Dashboard') }}</a>
                    </li>
                    @foreach ($breadcrumb as $item)
                        <li @class(['breadcrumb-item', 'active' => $loop->last])>
                        @if (isset($item['link']))
                            <a href="{{ $item['link'] }}">{{ $item['text'] }}</a>
                            @else
                            {{ $item['text'] }}
                            @endif
                            </li>
                            @endforeach
                </ol>
            </nav>
        </div>
        <div class="float-end">
            @if ($button)
                @can($url)
                <a href="{{ route($url) }}" class="btn btn-primary">
                    @if (strtolower($btn_title) == 'back')
                        <i class="bi bi-arrow-left"></i> {{ $btn_title }}
                    @elseif (strtolower($btn_title) == 'add new')
                        {{ $btn_title }} <i class="bi bi-plus-circle"></i>
                    @else
                        {{ $btn_title }}
                    @endif
                </a>
                @endcan
            @endif
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-body pt-3">
            {{ $slot }}
        </div>
    </div>
</section>
