<!DOCTYPE html>
<html lang="en">

<head>
@include('components.meta')

<!-- Library CSS style start-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> <!-- Bootstrap 5.3.3 -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- Boxicons -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> <!-- Animate on scroll -->
<link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet"> <!-- toastr -->
<!-- Library CSS style end -->

<!-- Main CSS File -->
<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

@if(isset($title))
    <title>{{$title}}</title>
    @else
    <title>Laravel Foundation</title>
    @endif
@stack('css-styles')
<style>
/* body { background: #c2e0ed; } */
:root { --sidebar-width: 220px; }
a:hover { color: inherit }

/* ========================== Navigation start ========================== */
#sidebar-dashboard {
    z-index: 9;
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: var(--sidebar-width);
    transition: all ease-in-out 0.5s;
    transition: all 0.5s;
    overflow-y: auto;
    background: #313a46;
    color: #8391a2;
    font-family: var(--bs-font-sans-serif);
} .mobile-nav-toggle {
    position: fixed;
    right: 15px;
    top: 15px;
    z-index: 9;
    border: 0;
    font-size: 1.4rem;
    transition: all 0.4s;
    outline: none !important;
    color: #fff;
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    line-height: 0;
    border-radius: 50px;
} .mobile-nav-active {
  overflow: hidden;
} .mobile-nav-active #sidebar-dashboard {
  left: 0;
}
.nav-menu * { margin: 0; padding: 0; list-style: none; }
.nav-menu ul { width: 100%; }
.nav-menu > ul > li {
  position: relative;
  white-space: nowrap;
}
.nav-menu .nav-link, .nav-menu li:focus {
  display: flex;
  align-items: center;
  margin-bottom: 8px;
  transition: 0.3s;
  font-size: .9rem;
}
.nav-menu li i, .nav-menu li:focus i {
  font-size: .8rem;
  padding-right: 8px;
}
.nav-menu li:hover, .nav-menu .active, .nav-menu .active:focus, .nav-menu li:hover > a {
  text-decoration: none;
  cursor: pointer;
  color: #ddd;
}
.dropdown-divider { margin: 0 20px; }
.nav-drop { position: absolute; right: 0; }
.nav-submenu { text-indent: 2em; margin-bottom: 10px; }
.nav-list { font-size: .8rem; }

#btn-close-sidebar { position: absolute; top: 6pt; right: 6pt; display: none; }
/* ========================== Navigation end ========================== */

#btn-notification {
    z-index: 10;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    color: #333333;
    background: #dddddd;
    border: none;
    outline: none;
    border-radius: 50%;
} #btn-notification:hover { background: #ccc; }

#section-header-dashboard { position: relative; }

#main, #container-header-dashboard {
    padding-left: var(--sidebar-width);
}

@media (max-width: 768px) {
    #section-content { padding: 30px 1%; }
}

@media (max-width: 1199px) {
    #toggle-sidebar { display: none; }
    #main, #container-header-dashboard { padding-left: 0 }
    #sidebar-dashboard {
        left: calc(var(--sidebar-width) * -1);
    }
}
/* ========================== Navigation end ========================== */
@media (max-width: 768px) {
}

@media (max-width: 1199px) {
    #btn-close-sidebar { display: block; }
    #btn-notification {
        position: fixed;
        bottom: 15px;
        right: 15px;
    }
}
</style>
</head>
<body>
<!-- ======= Mobile nav toggle button ======= -->
{{-- <i type="button" class="bx bx-menu mobile-nav-toggle bg-dark d-xl-none"></i> --}}

 <!-- ======================================= sidebar start ================================================== -->
 <header>
    <div id="sidebar-dashboard" class="d-flex flex-column flex-shrink-0">
        <i type='button' onclick="toggleSidebar()" id="btn-close-sidebar" class='bx bx-x'></i>
        <div id="sidebar-profile" class="p-3">
            <a href="/" class="mb-1">
                <h5 style="color: #ddd">Laravel <b style="color: #3bb6da">Foundation</b></h5>
            </a>
        </div>
        <div id="sidebar-menu" class="px-3">
            <nav class="nav-menu navbar">
                <ul>
                    <a href="/">
                        <li id="link-dashboard-home" class="nav-link mb-3">
                            <i class="bx bxs-home me-1"></i><span>Home</span>
                        </li>
                    </a>
                    @if(auth()->user()->web_role->name == 'admin' || auth()->user()->web_role->name == 'superadmin')
                    <li id="link-dashboard-admin" class="nav-link mb-3">
                        <i class="bx bxs-user-circle me-1"></i>
                        <span role="button" data-bs-toggle="collapse" data-bs-target="#submenu-admin" aria-expanded="true" aria-controls="submenu-admin" class="center">
                            Admin
                            <i class='bx bx-chevron-down nav-drop'></i>
                        </span>
                    </li>
                    <ul class="bx-ul collapse nav-submenu mb-3" id="submenu-admin">
                        <li id="link-dashboard-admin-users" class="nav-list">
                            <a href='/admin/users'>Users</a>
                        </li>
                    </ul>
                    @endif
                    <a href="/logout">
                        <li id="link-wizard" class="nav-link mb-3">
                            <i class="bx bx-log-out-circle me-1"></i><span>Log out</span>
                        </li>
                    </a>
                </ul>
            </nav>
        </div>
    </div>
</header>
<!-- ======================================= sidebar end ================================================== -->

<div id="section-header-dashboard" class="bg-white px-2 py-3 shadow">
    <div id="container-header-dashboard" class="container-fluid">
        <div class="w-100 flex-between">
            <div class="flex-start gap-2">
                <i type='button' onclick="toggleSidebar()" class='bx bx-menu text-dark font-16'></i>
                <h1 class="font-18 display-5 flex-start text-darkBlue mb-0"></h1>
            </div>

            <div class="center gap-3 font-8em">
                <img src="{{ auth()->user()->profile_picture_url }}" alt="" class="max-h-30px shadow-lg rounded-circle">
                <div class="dropdown">
                    <span role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ auth()->user()->username != '' ? auth()->user()->username : auth()->user()->email }}
                    </span>
                    <ul class="dropdown-menu font-8em">
                        <li><a class="dropdown-item center-start gap-2" href="/account/profile"><i class="bx bx-user"></i>My account</a></li>
                        <li><a class="dropdown-item center-start gap-2" href="/account/setting"><i class="bx bx-cog"></i>Setting</a></li>
                        <li><a class="dropdown-item center-start gap-2" href="/logout"><i class="bx bx-log-out-circle"></i>Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======= Main content ======= -->
<main id="main">
@yield('content')
</main>
<!-- ======= Main content end ======= -->

<!-- modal notification start -->
<!-- modal notification end -->

<!-- Library JS script start -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> <!-- jQuery 3.7.1 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  <!-- Bootstrap 5.3.3 -->
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script> <!-- Boxicons -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> <!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Sweetalert 2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> <!-- Toastr -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script> <!-- Animate on scroll -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script> <!-- Glightbox -->
<!-- Library JS script end -->

<!-- JS Files -->
<script type="text/javascript" src="{{ asset('/js/app.js') }}?v=1.1"></script>

<script type="text/javascript">
const user_id = '{{Auth::user()->id}}';

// toggle sidebar
var sidebar_show = true;
var sidebar_width = $('#sidebar-dashboard').outerWidth();
$('.mobile-nav-toggle').click(function() {
    $(this).toggleClass('bx-menu bx-x');
    toggleSidebar(sidebar_show);
});
$(window).resize(function() {
    if($(window).width() < 1199) {
        $('#sidebar-dashboard').css('left', sidebar_width*-1);
        $('#container-header-dashboard').css('padding-left', 0);
        $('#main').css('padding-left', 0);
        sidebar_show = false;
    } else {
        $('#sidebar-dashboard').css('left', 0);
        $('#container-header-dashboard').css('padding-left', sidebar_width);
        $('#main').css('padding-left', sidebar_width);
        sidebar_show = true;
    }
});
function toggleSidebar() {
    if(sidebar_show == true) {
        $('#sidebar-dashboard').css('left', sidebar_width*-1);
        if($(window).width() > 1199) {
          $('#container-header-dashboard').animate({'padding-left': 0});
          $('#main').animate({'padding-left': 0});
        }
        sidebar_show = false;
    } else {
        $('#sidebar-dashboard').css('left', 0);
        if($(window).width() > 1199) {
          $('#container-header-dashboard').animate({'padding-left': sidebar_width});
          $('#main').animate({'padding-left': sidebar_width});
        }
        sidebar_show = true;
    }
};

$(document).ready(function(){
  if($(window).width() < 1199) {
      $('#sidebar-dashboard').removeClass('show');
      sidebar_show = false;
  } else {
      $('#sidebar-dashboard').removeClass('show').addClass('show');
      sidebar_show = true;
  }
  @if(session('success'))
    successMessage("{{ session('success') }}");
  @elseif(session('error'))
    errorMessage("{{ session('error') }}");
  @elseif(session('info'))
    infoMessage("{{ session('info') }}");
  @endif
});

function successMessage(message) { toastr.success(message, 'Success!'); }
function infoMessage(message) { toastr.info(message, 'Info'); }
function warningMessage(message) { toastr.error(message, 'Warning!'); }
function errorMessage(message) { toastr.error(message, 'Error!'); }
</script>

@stack('scripts')
</body>

</html>
