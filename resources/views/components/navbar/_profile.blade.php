<div class="d-none d-md-flex me-3">
    <a class="nav-link px-0" data-bs-toggle="offcanvas" href="#otherMenu" aria-controls="otherMenu">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="icon icon-tabler icons-tabler-outline icon-tabler-category">
            <path stroke="none" d=" M0 0h24v24H0z" fill="none"/>
            <path d="M4 4h6v6h-6z"/>
            <path d="M14 4h6v6h-6z"/>
            <path d="M4 14h6v6h-6z"/>
            <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/>
        </svg>
    </a>
    <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"
       data-bs-placement="bottom">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
             stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z"/>
        </svg>
    </a>
    <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip"
       data-bs-placement="bottom">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
             stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/>
            <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"/>
        </svg>
    </a>
</div>

<div class="nav-item dropdown">
    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu"
       data-bs-target="#dropdownMenu">
        <span class="avatar avatar-sm" style="background-image: url('{{ asset('/public/img/profile.png') }}')"></span>
        <div class="d-none d-xl-block ps-2">
            <div>{{ session()->get('pegawai')->nama }}</div>
            <div class="mt-1 small text-muted">{{ session()->get('pegawai')->jbtn }}</div>
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" id="dropdownMenu">
        <a href="{{ url('setting') }}" class="dropdown-item"><i class="ti ti-settings me-2"></i> Setting</a>
        <a href="{{ url('setting/user') }}" class="dropdown-item"><i class="ti ti-user me-2"></i> Profile</a>
        <form action="{{ url('logout') }}" method="post">
            <input type="hidden" value="{{ csrf_token() }}" name="_token">
            <input type="hidden" value="{{ Request::segment(1) }}" name="href">
            <button class="dropdown-item"><i class="ti ti-logout me-2"></i> Logout</button>
        </form>
    </div>
</div>
{{-- main Menu bar --}}
