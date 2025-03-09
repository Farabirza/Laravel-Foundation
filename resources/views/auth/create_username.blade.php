@extends('layouts.master')

@push('css-styles')
<style>
.header {
  position:relative;
  background: linear-gradient(60deg, rgba(36,98,255,1) 0%, rgba(0,172,193,1) 100%);
}

.inner-header {
  height:85vh;
  width:100%;
  margin: 0;
  padding: 0;
}

.waves {
  position:relative;
  width: 100%;
  height:15vh;
  margin-bottom:-7px; /*Fix for safari gap*/
  min-height:100px;
  max-height:150px;
}

#btn-check-username:hover { background: #61a0fd; color: #fff; transition: ease-in-out .2s; }

/* Animation */

.parallax > use {
  animation: move-forever 25s cubic-bezier(.55,.5,.45,.5) infinite;
}
.parallax > use:nth-child(1) {
  animation-delay: -2s;
  animation-duration: 7s;
}
.parallax > use:nth-child(2) {
  animation-delay: -3s;
  animation-duration: 10s;
}
.parallax > use:nth-child(3) {
  animation-delay: -4s;
  animation-duration: 13s;
}
.parallax > use:nth-child(4) {
  animation-delay: -5s;
  animation-duration: 20s;
}
@keyframes move-forever {
  0% {
   transform: translate3d(-90px,0,0);
  }
  100% {
    transform: translate3d(85px,0,0);
  }
}

.card {
    min-width: 768px;
    border-radius: 30px;
    background: #f9f9f9;
    box-shadow: 15px 15px 30px #bebebe,
                -15px -15px 30px #ffffff;
    padding: 1.4rem 2rem;
    margin: 0 auto;
}


#form-create-username svg:active { transform: scale(1.2); }

@media (max-width: 768px) {
    .card { min-width: 0; }
    .inner-header { padding: 0 20px; }
    /* .waves {
        height:40px;
        min-height:40px;
    } */
    .content {
        height: 30vh;
    }
    h1 { font-size: 24px; }
}
</style>
@endpush

@section('content')

<div class="header">

<!--Content before waves-->
<div class="inner-header center">
    <form id="form-create-username" action="/auth/google/register" method="post" class="m-0">
    @csrf
    <div class="container">
        <div class="w-100 p-4 card shadow">
            <h3 class="font-16">Sign Up</h3>
            <p class="font-8 text-secondary mt-2">gunakan kombinasi huruf dan angka untuk membuat identitas unik akun anda</p>
            @if(isset($user))
            <div class="mt-3 text-primary">
                <b>{{$user->name}}</b> | {{$user->email}}
            </div>
            <input type="hidden" name="google_email" value="{{$user->email}}">
            <input type="hidden" name="google_id" value="{{$user->google_id}}">
            @endif
            <div class="mt-3 form-floating">
                <input type="text" name="username" id="check-username" class="form-control" placeholder="Username" required>
                <label for="username" class="form-label">Username</label>
            </div>
            <div class="mt-3">
                <p id="username-check-result" class="mb-0 font-9"><span class="fst-italic text-secondary"></span></p>
            </div>
            <div class="center-end gap-3 mt-3">
                <hr class="col">
                <svg type="button" onclick="back()" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6" style="max-height: 16pt;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                <svg type="button" onclick="submit()" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="max-height: 16pt;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>
    </div>
    </form>
</div>
<!--Waves Container-->
<div>
    <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
    viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
    <defs>
    <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
    </defs>
    <g class="parallax">
    <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
    <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
    <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
    <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
    </g>
    </svg>
</div>
<!--Waves end-->

</div>

@endsection

@push('scripts')
<script type="text/javascript">
var typingTimer;
const typingDelay = 400;
var isLoading = false;
var isVerified = false;

const back = () => { window.location = '/'; };
const submit = () => { $('#form-create-username').submit(); };

$(document).ready(function() {
    $('#btn-auth-signin').css('display', 'none');
});

$('#form-create-username').on('submit', function(e) {
    if(!isVerified) {
        e.preventDefault();
        infoMessage("Silahkan lengkapi username untuk akun anda");
    };
});

$('#form-create-username input[name="username"]').on('keyup change', function(e) {
    isVerified = false;
    $('#btn-submit-username').addClass('disabled');
    let $result = $('#username-check-result');
    let $loader = $('#loader-invitation_name');
    let value = $(this).val();
    if(!isLoading) {
        $loader.html(`<div class="d-flex" style="width: 20px"><span class="spinner-sm"></span></div>`);
        isLoading = true;
    }
    clearTimeout(typingTimer);
    typingTimer = setTimeout(function() {
        $result.html(`<span class="fst-italic text-secondary">*) silahkan periksa ketersediaan username sebelum melanjutkan</span>`);
        handleUsername();
        isLoading = false;
        $loader.html('');
    }, typingDelay);
});

const handleUsername = () => {
    var $result = $('#username-check-result');
    $result.html(`<div class="center w-20px"><span class="spinner-sm"></span></div>`);
    var config = {
        method: 'post', url: domain + '/ajax/auth',
        data: {
            action: 'check_username', username: $('#check-username').val(),
        },
    }
    axios(config)
    .then((response) => {
        if(response.data.available) {
            $result.html('<span class="text-success"><i class="bx bx-check-double me-1" ></i>' + response.data.message + '</span>');
            isVerified = true;
        } else {
            isVerified = false;
            if(response.data.message.length > 1) {
                $result.html('');
                response.data.message.forEach(messages);
                function messages(item, index) {
                    $result.append('<span class="text-danger"><i class="bx bx-error me-1" ></i>' + item + '</span><br/>');
                }
            } else {
                $result.html('<span class="text-danger"><i class="bx bx-error me-1" ></i>' + response.data.message + '</span>');
            }
        }
    })
    .catch((error) => {
        isVerified = false;
        console.log(error);
        if(error.response) {
            if(error.response.data.message) { errorMessage(response.message); }
        }
    });
}

</script>
@endpush
