<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/fnbb-logo.png') }}" alt="FNBB Logo" height="40">
        </a>
        
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" 
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Items -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                </li>
                
                @auth
                    @if(Auth::user()->isCustomer())
                        <!-- Customer-specific navigation -->
                        @if(Auth::user()->customerProfile)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('verification.*') ? 'active' : '' }}" 
                                   href="{{ route('verification.omang') }}">
                                    <i class="fas fa-id-card me-1"></i> Verification
                                </a>
                            </li>
                        @endif
                    @elseif(Auth::user()->isBankOfficer())
                        <!-- Bank Officer-specific navigation -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('officer.queue') ? 'active' : '' }}" 
                               href="{{ route('officer.queue') }}">
                                <i class="fas fa-tasks me-1"></i> Review Queue
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('officer.reports') ? 'active' : '' }}" 
                               href="{{ route('officer.reports') }}">
                                <i class="fas fa-chart-bar me-1"></i> Reports
                            </a>
                        </li>
                    @elseif(Auth::user()->isAdmin())
                        <!-- Admin-specific navigation -->
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-users me-1"></i> User Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-cog me-1"></i> System Settings
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
            
            <!-- Right Side Navigation -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>