<aside class="main-sidebar sidebar-dark-primary elevation-4">

    {{-- Brand --}}
    <a href="#" class="brand-link text-center">
        <span class="brand-text font-weight-light">
            Doctor Panel
        </span>
    </a>

    {{-- Sidebar --}}
    <div class="sidebar">

        {{-- Doctor Info --}}
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">
                    {{ auth()->user()->name }}
                </a>
            </div>
        </div>

        {{-- Menu --}}
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ url('/doctor/dashboard') }}"
                       class="nav-link {{ request()->is('doctor/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Bookings --}}
                <li class="nav-item">
                    <a href="{{ url('/doctor/bookings') }}"
                       class="nav-link {{ request()->is('doctor/bookings') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>My Bookings</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>


