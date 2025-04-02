<!doctype html>
<html lang="en">
<head>
<!-- Meta data -->
@include('components.meta.metaWeding')

<!-- Library CSS style start-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> <!-- Bootstrap 5.3.3 -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- Boxicons -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> <!-- Animate on scroll -->
<link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet"> <!-- Glightbox -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet"> <!-- toastr -->
<!-- Library CSS style end -->

<!-- Customized CSS style -->
<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

@if(isset($page_title))
<title>{{$page_title}}</title>
@else
<title>Laravel Foundation</title>
@endif
@stack('css-styles')
<style>
#preview-panel, #edit-panel {
    position: fixed;
    z-index: 999;
    background: #f1f1f1;
    width: 100%;
    padding: 4px 12px;
    border-bottom: 1px solid #ccc;
}
</style>
</head>

<body>
<!-- ======= Preview Panel Start ======= -->
@if(isset($preview))
<div id="preview-panel" class="center-between">
    <div>
        <a href="/dashboard/theme" class="center-start gap-2">
            <i class='bx bx-chevron-left font-14' ></i>
        </a>
    </div>
    <div>
        <p class="center gap-2 py-1">Preview <span class="text-coral">{{ $theme->name }}</span></p>
    </div>
</div>
@endif
<!-- ======= Preview Panel End ======= -->
<!-- ======= Edit Panel Start ======= -->
@if(isset($edit))
@if($theme->type == 'wedding') @include('components.sidebars.weddingEdit') @endif
<div id="edit-panel" class="center-between">
    <div>
        <a href="/" class="center-start gap-2">
            <i class='bx bx-chevron-left font-14' ></i>
        </a>
    </div>
    <div>
        <p class="center gap-3 py-1">
            <span id="toggle-offcanvas-invitation-edit" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-invitation-edit" aria-controls="offcanvas-invitation-edit"><i class='bx bx-menu'></i></span>
            <span class="center gap-2"></span>
        </p>
    </div>
</div>
@endif
<!-- ======= Edit Panel End ======= -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> <!-- Toastr -->
<script src="{{ asset('/vendor/popper/popper.min.js') }}"></script> <!-- Popper -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script> <!-- Animate on scroll -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script> <!-- Glightbox -->
<!-- Library JS script end -->

<!-- Customized JS script -->
<script type="text/javascript" src="{{ asset('/js/app.js') }}?v=1.1"></script>

@stack('scripts')

<!-- Configuration when entering preview mode start -->
@if(isset($preview))
<script type="text/javascript">
$(document).ready(function() {
    $('#modal-rsvp-btn-submit').remove();
});
</script>
@endif
<!-- Configuration when entering preview mode end -->

<script type="text/javascript">
const invitation_id = '{{ $invitation->id }}';
var isOwner = false;
@auth
isOwner = '{{ auth()->user()->id }}' == '{{ $invitation->user_id }}' ? true : false;
@endauth
@guest
$(document).on('contextmenu', function(e) {
    e.preventDefault();
});
@endguest

const showModal = (modal_id) => {
    $('.modal').modal('hide');
    $('#'+modal_id).modal('show');
}

// Countdown function start
var countdownFunction = null;
function startCountdown(countDownDate) {
    clearInterval(countdownFunction);
    countdownFunction = null;
    countdownFunction = setInterval(function() {
        var now = new Date().getTime();
        var distance = countDownDate - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        $('#countdown-days').text(days);
        $('#countdown-hours').text(hours);
        $('#countdown-minutes').text(minutes);
        $('#countdown-seconds').text(seconds);

        if (distance < 0) {
            clearInterval(countdownFunction);
            $('#countdown-days').text(0);
            $('#countdown-hours').text(0);
            $('#countdown-minutes').text(0);
            $('#countdown-seconds').text(0);
        }
    }, 1000);
}
// Countdown function end

$(document).ready(function() {
    let countDownDate = new Date("{{ $data->countdown_time }}").getTime();
    startCountdown(countDownDate);

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
