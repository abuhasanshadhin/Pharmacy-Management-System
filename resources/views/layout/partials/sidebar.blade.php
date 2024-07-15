<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        @foreach (Menu::items() as $item)
            @if (isset($item['children']) && count($item['children']))
                @if(auth()->user()->hasAnyPermission($item['permission']))
                    <li class="nav-item">
                        <a href="#" class="nav-link collapsed {{ isCollapse($item['children']) }}"
                           data-bs-target="#{{ Str::kebab($item['label']) }}"
                           data-bs-toggle="collapse">
                            {!! $item['icon'] ?? null !!}
                            <span class="label">{{ translator($item['label']) }}</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul id="{{ Str::kebab($item['label']) }}"
                            class="nav-content collapse {{ isCollapse($item['children']) }}"
                            data-bs-parent="#sidebar-nav">
                            @foreach ($item['children'] as $child)
                                @if(isset($child['url']))
                                    @can($child['url'])
                                        <li>
                                            <a href="{{ isset($child['url']) ? route($child['url']) : '#' }}"
                                               class="{{ isset($child['url']) ? isActive($child['url']) : '' }}">
                                                {!! $child['icon'] !!}
                                                <span class="label">{{ translator($child['label']) }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            @else
                @can($item['url'])
                    <li class="nav-item">
                        <a href="{{ isset($item['url']) ? route($item['url']) : '#' }}"
                           class="nav-link {{ isset($item['url']) ? isActive($item['url']) :'' }}">
                            {!! isset($item['icon']) ?$item['icon']: 'bi bi-journals' !!}
                            <span class="label">{{ translator($item['label']) }}</span>
                        </a>
                    </li>
                @endcan
            @endif
        @endforeach
    </ul>
    <div class="software-version">
        <div class="icon">
            <i class="bi bi-app-indicator"></i>
        </div>
        <h4 class="version">{{ translator('Version') }} 1.0</h4>
    </div>
</aside>
