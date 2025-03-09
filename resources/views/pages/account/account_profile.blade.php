@extends('layouts.dashboard')

@push('css-styles')
<style>
#content-wrapper { padding: 2.75rem 2.25rem; }
.form-control-sm, .form-select-sm { font-size: .7rem; }
.alert-danger { font-size: .7rem; padding: .5rem; margin-top: .5rem; }

@media (max-width: 1199px) {
}
</style>
@endpush

@section('content')

<div id="content-wrapper">
    <div class="center-between">
        <h5>User Profile</h5>
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
                    <div class="text-center font-8em">
                        <img src="{{ asset('images/assets/user.jpg') }}" alt="" class="img-fluid shadow-lg rounded-circle">
                        <div class="mt-4">
                            <input type="text" name="username" class="form-control p-1 border-0 border-bottom shadow-none text-center" value="{{ $user->username }}">
                            <p class="alert alert-danger d-none form-account-alert-username"></p>
                        </div>
                        <p class="text-secondary mt-2">{{ $user->email }}</p>
                        <div class="center mt-3">
                            <div class="p-2 rounded text-light font-8em" style="background: #3ab6da">{{ $user->web_role->name }}</div>
                        </div>
                        <div class="form-group mt-3">
                            <input type="password" name="password" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="Password">
                            <input type="password" name="password_confirmation" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="Confirm password">
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
                    <div class="form-group mt-3">
                        <label class="form-label">Full name</label>
                        <input type="text" name="full_name" value="{{ $profile->full_name }}" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="Full name">
                        <p class="alert alert-danger d-none form-profile-alert-full_name"></p>
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label">Address</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="address_country" value="{{ $profile->address_country }}" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="Country">
                                <p class="alert alert-danger d-none form-profile-alert-address_country"></p>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="address_city" value="{{ $profile->address_city }}" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="City">
                                <p class="alert alert-danger d-none form-profile-alert-address_city"></p>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="address_street" value="{{ $profile->address_street }}" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none" placeholder="Street">
                                <p class="alert alert-danger d-none form-profile-alert-address_street"></p>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="zip_code" value="{{ $profile->zip_code }}" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none input-numeric-only input-limit" data-max="5" placeholder="ZIP">
                                <p class="alert alert-danger d-none form-profile-alert-zip_code"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label">Contact</label>
                        <div class="center flex-wrap gap-2">
                            <select name="phone_code" class="form-control form-select-sm p-1 border-0 border-bottom shadow-none max-w-180px">
                                @foreach ($ipc as $item)
                                    <option value="{{ $item['code'] }}" @if($item['code'] == $profile->phone_code) selected @endif>{{ $item['country'] }} {{ $item['code'] }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="phone_number" value="{{ $profile->phone_number }}" class="form-control form-control-sm p-1 border-0 border-bottom shadow-none col input-numeric-only" placeholder="Contact">
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
