@push('css-styles')
<style>
.alert-danger { font-size: .7rem; padding: .5rem; margin-top: .5rem; }
.btn-google {
    border-radius: .4rem;
    box-shadow: 6px 6px 10px -1px rgba(0, 0, 0, 0.15), -6px -6px 10px -1px rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(0, 0, 0, 0);
    transition: transform 0.5s;
    color: #404040;
}
.btn-google i { color: #DB4437; }
.btn-google:hover {
    box-shadow:
        inset 4px 4px 6px -1px rgba(0, 0, 0, 0.2),
        inset -4px -4px 6px -1px rgba(255, 255, 255, 0.7),
        -0.5px -0.5px 0px rgba(255, 255, 255, 1),
        0.5px 0.5px 0px rgba(0, 0, 0, 0.15),
        0px 12px 10px -10px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.1);
    transform: translateY(0.2em);
}
.btn-google:hover, .btn-google:hover i { color: #666;  }
</style>
@endpush

<div id="modal-auth" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 id="modal-auth-title" class="modal-title center gap-3 fw-semibold">Title</h5>
                    <button type="button" class="btn-close btn-cancel" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="modal-auth-form" action="/login" method="post">
                @csrf
                <div class="my-3">
                    <div id="modal-auth-alert" class="alert alert-danger text-sm p-2 mb-3 d-none"></div>
                    <div class="form-floating mb-3" id="modal-auth-form-email">
                        <input type="text" name="email" id="modal-auth-input-email" class="form-control form-sm" placeholder="email">
                        <label for="modal-auth-input-email" class="label">Email</label>
                        <div id="alert-email" class="alert alert-danger text-sm p-2 mt-2 d-none"></div>
                    </div>
                    <div class="form-floating mb-3" id="modal-auth-form-password">
                        <input type="password" name="password" id="modal-auth-input-password" class="form-control form-sm" placeholder="password">
                        <label for="modal-auth-input-password" class="label">Password</label>
                        <div id="alert-password" class="alert alert-danger text-sm p-2 mt-2 d-none"></div>
                    </div>
                    <div class="form-floating mb-3" id="modal-auth-form-password_confirmation">
                        <input type="password" name="password_confirmation" id="modal-auth-input-password_confirmation" class="form-control form-sm" placeholder="password">
                        <label for="modal-auth-input-password_confirmation" class="label">Confirm Password</label>
                        <div id="alert-password_confirmation" class="alert alert-danger text-sm p-2 mt-2 d-none"></div>
                    </div>
                    <div class="form-outline mb-3" id="modal-auth-form-remember">
                        <p class="center-start gap-3 text-muted font-9em" style="color: #393f81;"><input type="checkbox" name="remember" value="true"> Remember me</p>
                    </div>
                    <div id="modal-auth-switch" class="font-9em"></div>
                    <div class="mt-3 center gap-3 text-secondary"><hr class="col">or<hr class="col"></div>
                    <div class="py-3">
                        <a href="/auth/google" class="w-100 btn-google center px-4 py-2" type="button"><i class='bx bx-xs bxl-google me-2'></i>Login with Google</a>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <button id="modal-auth-btn-submit" type="submit" class="btn btn-sm btn-dark">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal-password-recovery" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="modal-title center gap-3 fw-semibold"><i class="bx bx-key"></i>Recover password</h5>
                    <button type="button" class="btn-close btn-cancel" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="my-3">
                    <form action="/ajax/auth" method="post" id="form-recovery-otp" class="">
                    <input type="hidden" name="action" value="send-otp">
                    <div id="modal-password-recovery-alert" class="alert alert-danger text-sm p-2 mb-3 d-none"></div>
                    <div class="form-floating">
                        <input type="text" name="email" class="form-control form-sm" placeholder="email" autocomplete="off">
                        <label class="label">Email</label>
                        <p class="alert alert-danger d-none form-recovery-otp-alert-email"></p>
                    </div>
                    <div class="mt-3 center-between">
                        <div class="spinner-container">
                            <div id="countdown" class="font-9em"></div>
                            <div class="spinner spinner-sm d-none"></div>
                        </div>
                        <button type="submit" class="btn btn-outline-dark rounded-pill font-7em py-2 px-3">Send OTP Code</button>
                    </div>
                    </form>
                    <hr>
                    <div class="mt-3">
                        <p class="font-8em">An email will be sent to your address. Check your inbox to get the OTP code and enter it into the field below along with the new password.</p>
                    </div>
                    <form action="/ajax/auth" method="post" id="form-submit-otp">
                    <input type="hidden" name="action" value="submit-otp">
                    <div class="alert alert-success text-sm p-2 mt-3 d-none"></div>
                    <div class="form-group mt-3">
                        <div class="alert alert-danger d-none form-submit-otp-alert-otp"></div>
                        <div class="center-around gap-2">
                            <input type="text" name="otp_code[0]" class="otp-input form-control form-control-sm p-1 border-0 border-bottom shadow-none text-center fw-bold" maxlength="1" autocomplete="off" placeholder="-">
                            <input type="text" name="otp_code[1]" class="otp-input form-control form-control-sm p-1 border-0 border-bottom shadow-none text-center fw-bold" maxlength="1" autocomplete="off" placeholder="-">
                            <input type="text" name="otp_code[2]" class="otp-input form-control form-control-sm p-1 border-0 border-bottom shadow-none text-center fw-bold" maxlength="1" autocomplete="off" placeholder="-">
                            <input type="text" name="otp_code[3]" class="otp-input form-control form-control-sm p-1 border-0 border-bottom shadow-none text-center fw-bold" maxlength="1" autocomplete="off" placeholder="-">
                            <input type="text" name="otp_code[4]" class="otp-input form-control form-control-sm p-1 border-0 border-bottom shadow-none text-center fw-bold" maxlength="1" autocomplete="off" placeholder="-">
                            <input type="text" name="otp_code[5]" class="otp-input form-control form-control-sm p-1 border-0 border-bottom shadow-none text-center fw-bold" maxlength="1" autocomplete="off" placeholder="-">
                        </div>
                        <div class="alert alert-danger d-none form-submit-otp-alert-otp_codes"></div>
                    </div>
                    <div class="form-group mt-3">
                        <input type="password" name="password" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="New password">
                    </div>
                    <div class="form-group mt-3">
                        <input type="password" name="password_confirmation" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="Confirm new password">
                        <div class="alert alert-danger form-submit-otp-alert-password text-sm p-2 mt-2 d-none"></div>
                    </div>
                    <div class="center-between mt-3">
                        <div class="font-9em">Back to <span type="button" class="text-primary hover-underline" onclick="showAuthModal('login')">sign in</span></div>
                        <button type="submit" class="btn btn-success rounded-pill font-7em px-3">Reset password</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
// ==================== Authentication Start ==================== //
var authAction = '/';
$('#modal-auth-btn-submit').on('submit', function(e) {
    e.preventDefault();
    $('.alert').hide();
    let form = $('#modal-auth-form');
    let formData = new FormData(form[0]);
    let config = {
        method: form.attr('method'), url: domain + authAction, data: formData
    };
    axios(config)
    .then((response) => {
        $('#modal-auth').modal('hide');
        form.submit();
    })
    .catch((error) => {
        console.log(error.response.data);
        if(error.response.data.message) {
            $('#modal-auth-alert').hide().removeClass('d-none').fadeIn('slow').html(error.response.data.message);
        }
        if(error.response.data != undefined) {
            validationMessage(error.response.data.errors);
        }
    });
});
function showAuthModal(type) {
    $('.modal').modal('hide');
    $('#modal-auth-form')[0].reset();
    switchAuthModal(type);
    $('#modal-auth').modal('show');
}
function switchAuthModal(type) {
    switch(type) {
        case 'register':
            $('#modal-auth-title').html(`<i class="bx bx-user-plus"></i>Sign up`);
            $('#modal-auth-switch').html(`Already have an account? <span type="button" class="text-primary hover-underline" onclick="switchAuthModal('login')">Sign in</span>`);
            $('#modal-auth-form-password_confirmation').show();
            $('#modal-auth-form-remember').hide();
            authAction = '/api/register';
            $('#modal-auth-btn-submit').html('Register');
        break;
        case 'login':
            $('#modal-auth-title').html(`<i class='bx bx-log-in-circle'></i>Sign in`);
            $('#modal-auth-switch').html(`
                <p>Don't have an account? <span type="button" class="text-primary hover-underline" onclick="switchAuthModal('register')">Sign up</span></p>
                <p class="mt-2"><span type="button" class="text-primary hover-underline" onclick="showPasswordRecoveryModal()">Forgot your password?</span></p>
            `);
            $('#modal-auth-form-password_confirmation').hide();
            $('#modal-auth-form-remember').show();
            authAction = '/api/login';
            $('#modal-auth-btn-submit').html('Login');
        break;
    }
}
// ==================== Authentication End ==================== //

// ==================== Password Recovery Start ==================== //
function showPasswordRecoveryModal() {
    $('.modal').modal('hide');
    $('#modal-password-recovery').modal('show');
}

$('#form-recovery-otp').off('submit').on('submit', function(e) {
    e.preventDefault();
    $('.alert').hide().html('');
    $('#countdown').hide();

    let formName = $(this).attr('id');
    let $spinner = $(this).find('.spinner');
    let $alert_error = $('#modal-password-recovery-alert');
    let formData = new FormData($(this)[0]);
    let config = { method: $(this).attr('method'), url: domain + $(this).attr('action'), data: formData, };

    $spinner.hide().removeClass('d-none').fadeIn('slow');
    $alert_error.hide();
    axios(config)
    .then((response) => {
        $spinner.hide().addClass('d-none');
        successMessage(response.data.message);
        if(response.data.otp_exp) {
            startCountdown(response.data.otp_exp);
            $('#countdown').show();
        }
    })
    .catch((error) => {
        console.log(error);
        if(error.response.data.message) $alert_error.hide().removeClass('d-none').fadeIn('slow').html(error.response.data.message);
        if(error.response.data && error.response.data.errors) validationMessage(error.response.data.errors, formName);
        if(error.response.data.otp_exp) {
            startCountdown(error.response.data.otp_exp);
            $('#countdown').show();
        }
        $spinner.hide().addClass('d-none');
    });
});

$('#form-submit-otp').off('submit').on('submit', function(e) {
    e.preventDefault();
    $('.alert').hide().html('');

    let $form = $('#form-submit-otp');
    let $alert = $('.form-submit-otp-alert-otp');
    let formData = new FormData($(this)[0]);
    let email = $('#form-recovery-otp').find("input[name='email']").val();
    formData.append('email', email);

    $alert.html('').addClass('d-none');
    axios.post(domain + $(this).attr('action'), formData)
    .then((res) => {
        $form.find('.alert-success').hide().removeClass('d-none').fadeIn('slow').html(res.data.message);
        $form.trigger('reset');
    })
    .catch((err) => {
        let message = err.response.data.message ?? '';
        if(message != '') $alert.removeClass('d-none').show().html(message);
        if(err.response.data && err.response.data.errors) validationMessage(err.response.data.errors, 'form-submit-otp');
    });
});
// ==================== Password Recovery End ==================== //

let countdownInterval;
function startCountdown(targetDateTime) {
    clearInterval(countdownInterval); // Clear previous interval if exists

    let targetTime = new Date(targetDateTime.replace(/-/g, '/')); // Convert to Date object

    if (isNaN(targetTime)) {
        document.getElementById("countdown").innerText = "Invalid date format!";
        return;
    }

    countdownInterval = setInterval(() => {
        let now = new Date();
        let diff = targetTime - now;

        if (diff <= 0) {
            clearInterval(countdownInterval);
            document.getElementById("countdown").innerText = "";
            return;
        }

        let days = Math.floor(diff / (1000 * 60 * 60 * 24));
        let hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((diff % (1000 * 60)) / 1000);
        if(minutes < 10) minutes = '0'+minutes;
        if(seconds < 10) seconds = '0'+seconds;

        document.getElementById("countdown").innerText =
            `${minutes}:${seconds}`;
    }, 1000);
}

$(document).ready(function () {
    $(".otp-input").on("input", function () {
        let $this = $(this);
        let value = $this.val();

        // Move to next input if a number is entered
        if (value.length === 1) {
            $this.next(".otp-input").focus();
        }
    });

    $(".otp-input").on("keydown", function (e) {
        let $this = $(this);

        // Move to previous input on Backspace
        if (e.key === "Backspace" && $this.val() === "") {
            $this.prev(".otp-input").focus();
        }
    });

    // Restrict input to numbers only
    $(".otp-input").on("input", function () {
        this.value = this.value.replace(/[^0-9]/g, "");
    });
});

</script>
@endpush
