@extends('layouts.dashboard')

@push('css-styles')
<link href="{{ asset('/vendor/datatables/datatables.min.css') }}" rel="stylesheet">
<style>
#content-wrapper { padding: 2.75rem 2.25rem; }
.dropdown-item:hover { background: #d9d9d9; }

@media (max-width: 1199px) {
}
</style>
@endpush

@section('content')

<div id="content-wrapper">
    {{-- Breadcrumb start --}}
    <div class="center-between between mb-3">
        <h5>Users Controller</h5>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb font-8em">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item">Admin</li>
              <li class="breadcrumb-item active" aria-current="page">Users Controller</li>
            </ol>
        </nav>
    </div>
    {{-- Breadcrumb end --}}

    {{-- Content start --}}
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12 flex-between gap-3 p-0">
                    <div class="col">
                        <div class="border border-1 rounded p-3">
                            <p class="font-1em">Total users</p>
                            <p class="text-center font-24em fw-600">{{ count($users) }}</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="border border-1 rounded p-3">
                            <p class="font-1em">Registered today</p>
                            @php
                                $registeredToday = $users->filter(function ($user) {
                                    return $user->created_at->isToday();
                                })->count();
                            @endphp
                            <p class="text-center font-24em fw-600">{{ $registeredToday }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <table id="table-users" class="table font-9em">
                        <thead>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Registered at</th>
                            <th>Status</th>
                            <th></th>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>
                                    @if($user->status == 'active')
                                    <span class="text-success">{{ $user->status }}</span>
                                    @else
                                    <span class="text-danger">{{ $user->status }}</span>
                                    @endif
                                </td>
                                <td class="text-end">

                                    <div class="dropdown">
                                        <i class='bx bx-dots-vertical-rounded' type="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu font-8em">
                                            <li class="dropdown-item" type="button" onclick="userDetail(`{{ $user->id }}`)">
                                                <span>Detail</span>
                                            </li>
                                            <li class="dropdown-item" type="button">
                                                <span>Reset password</span>
                                            </li>
                                            <li class="dropdown-item" type="button">
                                                <span>Change status</span>
                                            </li>
                                            <li class="dropdown-item" type="button">
                                                <span>Change web role</span>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    {{-- Content end --}}
</div>


<!-- Modal user detail Start -->
<div class="modal fade" id="modal-user-detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header flex-between">
                <h5 class="modal-title center gap-3 fw-semibold">
                    <i class='bx bx-user'></i>
                    <span>User Detail</span>
                </h5>
                <button type="button" class="btn-close btn-cancel" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <img src="" alt="" class="user-detail-picture img-fluid border shadow-lg rounded-circle">
                    </div>
                    <div class="col-md-9 center-between">
                        <div class="col">
                            <p class="user-detail-username font-1em"></p>
                            <p class="user-detail-email text-secondary font-8em"></p>
                        </div>
                        <div class="col text-end font-8em">
                            <p class="text-secondary">Joined on:</p>
                            <p class="user-detail-created_at"></p>
                        </div>
                    </div>
                    <div class="col-md-12"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal user detail End -->
@endsection

@push('scripts')
<script src="{{ asset('/vendor/datatables/datatables.min.js') }}"></script>
<script type="text/javascript">
const toggleDetail = (index) => {
    let toggle = $('#toggle-detail-'+index);
    let isShown = toggle.attr('data-shown');
    let detail = $('#table-detail-'+index);
    if(isShown == 'true') {
        detail.addClass('d-none');
        toggle.attr('data-shown', 'false').html(`More <i class="bx bx-chevron-down"></i></span>`);
    } else {
        detail.removeClass('d-none');
        toggle.attr('data-shown', 'true').html(`Hide <i class="bx bx-chevron-up"></i></span>`);
    }
};

function userDetail(user_id) {
    let $modal = $('#modal-user-detail');

    $('.modal').modal('hide');
    axios.post('/ajax/admin', {
        action: 'user_detail', user_id: user_id
    })
    .then((res) => {
        if(res.data != '') {
            $('.user-detail-picture').attr('src', res.data[0].picture_url);
            $('.user-detail-username').html(res.data[0].username);
            $('.user-detail-email').html(res.data[0].email);
            $('.user-detail-created_at').html(res.data[0].created_date);
        }
        $modal.modal('show');
    })
    .catch((err) => {
        //
    });
}

$(document).ready(function() {
    $('#table-users').DataTable({
        order: [[3, 'desc'], [2, 'desc']], responsive: true,
    });
    $('#link-dashboard-admin').css({'font-weight': 600, 'color': '#ddd'});
    $('#submenu-admin').addClass('show');
    $('#link-dashboard-admin-users').css({'font-weight': 500, 'color': '#ddd'});
});
</script>
@endpush
