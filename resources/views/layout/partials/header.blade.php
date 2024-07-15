<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center
        justify-content-between me-4">
        <a href="/" class="logo d-flex align-items-center">
            @if(setting('logo_visible')  == 'application_name')
                <img src="{{ @globalAsset(setting('favicon')) }}" alt="logo">
                <span class=" d-none d-lg-block">{{ setting('app_name') }}</span>
            @else
                <img src="{{ @globalAsset(setting('logo')) }}" alt="logo">
            @endif
        </a>
        <a href="javascript:" class="toggle-sidebar-btn">
            <i class="bi bi-list sidebar-lg"></i>
            <i class="bi bi-filter-left sidebar-close"></i>
        </a>
    </div>
    <nav class="header-nav me-auto">
        <ul class="d-flex align-items-center">
            <li>
                <form class="d-flex global-search" role="search" action="#">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button><i class="bi bi-search"></i></button>
                </form>
            </li>
        </ul>
    </nav>
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown application">
                <a href="{{ route('system.restart') }}" class="nav-link nav-icon soft-bg" title="Clear Cache" >
                    <span class="solar--rocket-bold-duotone"></span>
                </a>
            </li>
            <li class="nav-item dropdown application">
                <a class="nav-link nav-icon soft-bg" title="Quick Options" href="#" data-bs-toggle="dropdown">
                    <span class="solar--widget-3-bold-duotone"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow apps">
                    <div class="app-row d-flex align-items-center ">
                        <a href="{{ route('sale.create') }}" class="app p-2">
                            <i class="solar--printer-bold-duotone"></i> {{ translator('POS') }}
                        </a>
                        <a href="{{ route('purchase.create') }}" class="app p-2">
                            <i class="solar--calculator-bold-duotone"></i> {{ translator('Purchase') }}
                        </a>
                    </div>
                    <div class="app-row d-flex align-items-center ">
                        <a href="{{ route('product.index') }}" class="app p-2">
                            <i class="solar--jar-of-pills-2-bold-duotone"></i> {{ translator('Medicines') }}
                        </a>
                        <a href="{{ route('stock.index') }}" class="app p-2">
                            <i class="solar--plus-minus-bold-duotone"></i> {{ translator('Stock') }}
                        </a>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link nav-icon " href="#" data-bs-toggle="dropdown">
                    <img class="rounded-1" height="18" width="18"
                         src="{{ asset('assets/img/flags/'.getCurrentLocale(app()->getLocale())) }}" alt="">
                    <small class="dropdown-toggle">{{ getCurrentLocale(app()->getLocale(), 'name') }}</small>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    @foreach(\App\Utils\Utilites::languages() ?? [] as $language)
                        <li class="d-flex gap-1">
                            <a class="dropdown-item mx-2 rounded-2" href="{{ route('change.lang',$language['lang']) }}">
                                <img class="rounded-1" height="20" width="20"
                                     src="{{ asset('assets/img/flags/'.$language['icon']) }}" alt="">
                                {{ $language['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link nav-icon soft-bg" href="#" data-bs-toggle="dropdown">
                    <span class="solar--bell-bold-duotone"></span>
                    <span class="badge bg-danger badge-number">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header">
                        {{ translator('You have 0 new notifications') }}
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">{{ translator('View all') }}</span></a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="dropdown-footer">
                        <a href="#" class="text-decoration-none">{{ translator('Show all notifications') }}</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex  align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <div class="d-flex gap-2">
                        <img src="{{globalAsset(auth()->user()->profile_image) }}" height="40" width="40" alt="Profile" class="rounded-circle">
                        <div class="d-flex flex-column">
                            <span>{{ auth()->user()->name }}</span>
                            <small class="text-muted">{{ roleName() }}</small>
                        </div>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile p-2">
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('user.profile') }}">
                            <span class="solar--user-rounded-bold-duotone"></span>
                            <span>{{ translator('My Profile') }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2"
                           href="{{ route('user.change-password') }}">
                            <span class="solar--password-minimalistic-input-bold-duotone"></span>
                            <span>{{ translator('Change Password') }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('logout') }}">
                            <span class="solar--logout-3-bold-duotone"></span>
                            <span>{{ translator('Sign Out') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
