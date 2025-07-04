@extends('layouts.dashboard')

@push('css-styles')
<style>
.form-control-sm, .form-select-sm { font-size: .7rem; }
.alert-danger { font-size: .7rem; padding: .5rem; margin-top: .5rem; }

.form-select-sm {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"><path d="M7 10l5 5 5-5z"/></svg>');
    background-size: 1.5em;
}

.item-overlay {
    opacity: 0;
    position: absolute !important;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
    background: linear-gradient(0deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.6));
    text-align: center;
    color: #202020;
}
.item-overlay:hover {
    opacity: 1;
    transition: .4s ease-in-out;
}

@media (max-width: 1199px) {
}
</style>
@endpush

@section('content')

<div id="content-wrapper">
    <div class="container center-between mb-4">
        <h5 class="page-title">User Profile</h5>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb font-8em">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item">Account</li>
              <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-3 px-3 mb-3">
                    <form action="/ajax/account" method="post" id="form-account" class="form-handler">
                    <input type="hidden" name="action" value="update-account">
                    <input type="hidden" name="picture_base64" class="form-account-base64">
                    <div class="text-center font-8em">
                        <div class="position-relative border shadow-lg rounded-circle overflow-hidden">
                            <div class="item-overlay">
                                <label for="form-account-picture" type="button"><i class="bx bx-camera font-24em"></i></label>
                            </div>
                            <img src="{{ auth()->user()->picture_url }}" alt="" class="account-picture-preview img-fluid">
                            <input type="file" name="picture" id="form-account-picture" accept="image/*" class="cropper-input d-none" data-cropper-type="basic" data-cropper-preview=".account-picture-preview" data-cropper-result=".form-account-base64">
                        </div>
                        <p class="fw-semibold mt-4">{{ $user->email }}</p>
                        @if($user->web_role->name != 'basic_user')
                        <div class="center mt-3">
                            <div class="p-2 rounded text-light font-8em" style="background: #3ab6da">{{ $user->web_role->name }}</div>
                        </div>
                        @endif
                        <div class="form-group mt-3">
                            <input type="password" name="password" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="Password" autocomplete="off">
                            <input type="password" name="password_confirmation" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="Confirm password" autocomplete="off">
                            <p class="alert alert-danger d-none form-account-alert-password"></p>
                        </div>
                        <div class="center-end mt-3">
                            <button type="submit" class="btn btn-outline-dark rounded-pill font-8em">Update</button>
                        </div>
                    </div>
                    </form>
                </div>

                {{-- Form User Profile Start --}}
                <div class="col-md-9 px-3 font-8em">
                    <form action="/ajax/account" method="post" id="form-profile" class="form-handler">
                    <input type="hidden" name="action" value="save-profile">
                    <h5>User Data</h5>
                    <div class="form-group center gap-3 mt-3">
                        <div class="col">
                            <label class="form-label">Full name</label>
                            <input type="text" name="full_name" value="{{ $user->full_name }}" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="Full name">
                            <p class="alert alert-danger d-none form-profile-alert-full_name"></p>
                        </div>
                        <div class="min-w-120px">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select form-select-sm p-1 border-0 border-bottom shadow-none max-w-180px">
                                <option value="" @if($user->gender == '') selected @endif>Select</option>
                                <option value="male" @if($user->gender == 'male') selected @endif>Male</option>
                                <option value="female" @if($user->gender == 'female') selected @endif>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label">Address</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="address_country" value="{{ $user->address_country }}" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="Country">
                                <p class="alert alert-danger d-none form-profile-alert-address_country"></p>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="address_city" value="{{ $user->address_city }}" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="City">
                                <p class="alert alert-danger d-none form-profile-alert-address_city"></p>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="address_street" value="{{ $user->address_street }}" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="Street">
                                <p class="alert alert-danger d-none form-profile-alert-address_street"></p>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="zip_code" value="{{ $user->zip_code }}" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none input-numeric-only input-limit" data-max="5" placeholder="ZIP">
                                <p class="alert alert-danger d-none form-profile-alert-zip_code"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label">Contact</label>
                        <div class="center flex-wrap gap-2">
                            <select name="phone_code" class="form-select form-select-sm p-1 border-0 border-bottom shadow-none max-w-180px">
                                @foreach ($ipc as $item)
                                    <option value="{{ $item['code'] }}" @if($item['code'] == $user->phone_code) selected @endif>{{ $item['country'] }} {{ $item['code'] }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="phone_number" value="{{ $user->phone_number }}" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none col input-numeric-only" placeholder="Contact">
                        </div>
                    </div>
                    <div class="center-end mt-4">
                        <button type="submit" class="btn btn-outline-success center-start gap-2 rounded-pill font-8em"><i class="bx bx-save"></i>Save</button>
                    </div>
                </form>
                </div>
                {{-- Form User Profile End --}}

            </div>
        </div>
    </section>
</div>

@include('components.modals.modalCropper')

@endsection

@push('scripts')
<script type="text/javascript">

$(document).ready(function() {
    $('#form-account').submit(function() {
        $(this).find("input[name='password']").val('');
        $(this).find("input[name='password_confirmation']").val('');
    });
});
</script>
@endpush
