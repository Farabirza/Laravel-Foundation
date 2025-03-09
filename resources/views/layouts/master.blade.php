<!doctype html>
<html lang="en">
<head>
<!-- Meta data -->
@include('components.meta.metaIndex')
@guest
@include('components.modals.modalAuth')
@endguest

<!-- Library CSS style start-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> <!-- Bootstrap 5.3.3 -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- Boxicons -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> <!-- Animate on scroll -->
<link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
<link href="{{ asset('/vendor/toastr/toastr.min.css') }}" rel="stylesheet"> <!-- toastr -->
<!-- Library CSS style end -->

<!-- Customized CSS style -->
<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

@if(isset($page_title))
<title>{{$page_title}}</title>
@else
<title>ruangnamu.com</title>
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> <!-- jQuery 3.7.1 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  <!-- Bootstrap 5.3.3 -->
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script> <!-- Boxicons -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> <!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Sweetalert 2 -->
<script src="{{ asset('/vendor/toastr/toastr.min.js') }}"></script> <!-- Toastr -->
<script src="{{ asset('/vendor/popper/popper.min.js') }}"></script> <!-- Popper -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script> <!-- Animate on scroll -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script> <!-- Glightbox -->
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
