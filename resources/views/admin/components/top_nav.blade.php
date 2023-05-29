<nav class="main-header navbar navbar-expand navbar-white navbar-light @if (session('isBorderBtm')) border-bottom-0 @endif">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link toggle-icon" data-widget="pushmenu" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
            </a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto nav-top-right">
        <li class="nav-item dropdown lang">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <p>
                    <img src='{{ asset('flag/' .session()->get('locale')) . '.svg' }}' alt="">
                    <span>{{ session()->get('locale') }}</span>
                </p>
                <span class="arrow-down">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="{{ route('changeLang', 'lang=en') }}" class="dropdown-item" >
                    <img src="{{ asset('flag/en.svg') }}" alt="en">
                    <span>EN</span>
                </a>
                <a href="{{ route('changeLang', 'lang=fr') }}" class="dropdown-item">
                    <img src="{{ asset('flag/fr.svg') }}" alt="fr">
                    <span>FR</span>
                </a>
                <a href="{{ route('changeLang', 'lang=es') }}" class="dropdown-item">
                    <img src="{{ asset('flag/es.svg') }}" alt="es">
                    <span>ES</span>
                </a>
                <a href="{{ route('changeLang', 'lang=zh') }}" class="dropdown-item">
                    <img src="{{ asset('flag/zh.svg') }}" alt="zh">
                    <span>ZH</span>
                </a>

            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <div class="user-profile">
                    <div class="image">
                        <img src="{{ auth()->user()->profilePic()  }}" class="img-circle elevation-2 navbar-profile" alt="{{ auth()->user()->name }}">
                    </div>
                    <p>{{ auth()->user()->name }}</p>
                    <span class="arrow-down">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="{{ route('admin.profile') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> {{ __('Profile') }}
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.setup') }}" class="dropdown-item">
                    <i class="fas fa-cogs mr-2"></i> {{ __('Setup') }}
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item admin-logout" href="{{ route('logout') }}">
                    <i class="fas fa-power-off mr-2"></i> {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="no-display logout-form">
                    @csrf
                </form>
            </div>
        </li>
        <li class="nav-item setting-control">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </a>
       </li>
    </ul>
</nav>
