@push('css-styles')
<style>
    /* #4285f4 */
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
                        <p class="text-muted" style="color: #393f81;"><input type="checkbox" name="remember" value="true" class="me-1"> Remember me</p>
                    </div>
                    <p id="modal-auth-switch"></p>
                    <div class="mt-3 center gap-3 text-secondary"><hr class="col">or<hr class="col"></div>
                    <div class="py-3">
                        <a href="/auth/google" class="w-100 btn-google center px-4 py-2" type="button"><i class='bx bx-xs bxl-google me-2 font-16'></i>Login with Google</a>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <button id="modal-auth-btn-submit" type="button" class="btn btn-sm btn-dark">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
var authAction = '/';
$('#modal-auth-btn-submit').on('click', function(e) {
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
        if(error.response.data.alert) {
            $('#modal-auth-alert').hide().removeClass('d-none').fadeIn('slow').html(error.response.data.alert);
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
            $('#modal-auth-title').html(`<i class='bx bx-log-in-circle'></i>Login`);
            $('#modal-auth-switch').html(`Don't have an account? <span type="button" class="text-primary hover-underline" onclick="switchAuthModal('register')">Sign up</span>`);
            $('#modal-auth-form-password_confirmation').hide();
            $('#modal-auth-form-remember').show();
            authAction = '/api/login';
            $('#modal-auth-btn-submit').html('Login');
        break;
    }
}
</script>
@endpush
