<!doctype html>
<html lang="en">
<head>
<!-- Meta data -->
@include('components.meta.metaIndex')
@guest
@include('components.modals.modalAuth')
@endguest

<!-- Library CSS style start-->
<link href="{{ asset('/vendor/bootstrap/css/bootstrap-5.3.3.min.css') }}"  rel="stylesheet"> <!-- Bootstrap 5.3.3 -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- Boxicons -->
<link href="{{ asset('/vendor/aos/aos-2.3.1.min.css') }}"  rel="stylesheet"> <!-- Animate on scroll -->
<link href="{{ asset('/vendor/glightbox/glightbox.min.css') }}"  rel="stylesheet">
<link href="{{ asset('/vendor/toastr/toastr.min.css') }}" rel="stylesheet"> <!-- toastr -->
<!-- Library CSS style end -->

<!-- Customized CSS style -->
<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

@if(isset($page_title))
<title>{{$page_title}}</title>
@else
<title>Laravel Foundation</title>
@endif
@stack('css-styles')
</head>

<body>
<!-- ======= Main content ======= -->
<main id="main">
@yield('content')
</main>
<!-- ======= Main content end ======= -->

<!-- Library JS script start -->
<script src="{{ asset('/vendor/jquery/jquery-3.7.1.min.js') }}"></script> <!-- jQuery 3.7.1 -->
<script src="{{ asset('/vendor/bootstrap/js/bootstrap-5.3.3.bundle.min.js') }}"></script>  <!-- Bootstrap 5.3.3 -->
<script src="{{ asset('/vendor/boxicons/boxicons-2.1.4.js') }}"></script> <!-- Boxicons -->
<script src="{{ asset('/vendor/axios/axios-1.9.0.js') }}"></script> <!-- Axios -->
<script src="{{ asset('/vendor/sweetalert2/sweetalert2-11-20-0.all.min.js') }}"></script> <!-- Sweetalert 2 -->
<script src="{{ asset('/vendor/toastr/toastr.min.js') }}"></script> <!-- Toastr -->
<script src="{{ asset('/vendor/popper/popper.min.js') }}"></script> <!-- Popper -->
<script src="{{ asset('/vendor/aos/aos-2.3.1.min.js') }}"></script> <!-- Animate on scroll -->
<script src="{{ asset('/vendor/glightbox/glightbox.min.js') }}"></script> <!-- Glightbox -->
<!-- Library JS script end -->

@stack('scripts')

<!-- Customized JS script -->
<script type="text/javascript" src="{{ asset('/js/app.js') }}?v=1.1"></script>
<script type="text/javascript">
$(document).ready(function() {
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
</body>
</html>
