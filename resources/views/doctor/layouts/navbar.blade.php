<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    {{-- Left side --}}
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    {{-- Right side --}}
    <ul class="navbar-nav ml-auto">

        {{-- Doctor Name --}}
        <li class="nav-item">
            <span class="nav-link">
                👨‍⚕️ {{ auth()->user()->name }}
            </span>
        </li>

        {{-- Logout --}}
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-link nav-link text-danger">
                    Logout
                </button>
            </form>
        </li>

    </ul>
</nav>
