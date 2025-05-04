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

            {{-- Chartjs start --}}
            <div class="row mt-4">
                <div class="col-md-8">
                    <canvas id="chart-user-regis"></canvas>
                    <div class="mt-2">
                        <form id="form-chart-user-regis" action="">
                        <div class="center gap-3">
                            <select id="chart-user-regis-type" class="form-select form-select-sm max-w-120px" onchange="changePeriodeType('chart-user-regis')" autocomplete="false">
                                <option value="year">Year</option>
                                <option value="month">Month</option>
                            </select>
                            <div id="chart-user-regis-periode" class="col"></div>
                            <button class="btn btn-sm btn-primary center gap-2 max-w-120px" type="submit"><i class="bx bx-chart"></i>Generate</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Chartjs end --}}

            {{-- Datatable start --}}
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

// ---------- Chartjs start ---------- //
const months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
const curr_date = new Date();
const curr_year = curr_date.getFullYear();
const ctxUserRegis = document.getElementById('chart-user-regis');
const chartUserRegis = new Chart(ctxUserRegis, {
  type: 'line',
  data: {
    labels: [],
    datasets: [{
        label: 'User Registration',
        data: [],
        fill: true,
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.1
    }]
  },
  options: {
    responsive: true
  }
});
function changePeriodeType(id) {
    let $container = $('#'+ id +'-periode');
    let $type = $('#'+ id +'-type');
    let type = $type.val();
    let el = '';
    $container.html('');
    switch(type) {
        case 'year':
            el += '<select name="periode" class="form-select form-select-sm col">';
            for(let i = curr_year; i >= 1970; i--) el += `<option value="${i}">${i}</option>`;
            el += '</select>';
            $container.html(el);
        break;
        case 'month':
            el += '<div class="center gap-3"><select name="periode[0]" class="form-select form-select-sm col">';
            for(let i = curr_year; i >= 1970; i--) el += `<option value="${i}">${i}</option>`;
            el += '</select><select name="periode[1]" class="form-select form-select-sm col">';
            for(let i = 11; i >= 0; i--) el += `<option value="${i + 1}">${months[i]}</option>`;
            el += '</select></div>';
            $container.html(el);
        break;
    }
}
$('#form-chart-user-regis').on('submit', function(e) {
    e.preventDefault();
    let type = $('#chart-user-regis-type').val();
    let periode = new Date();
    switch(type) {
        case 'year':
            periode = $("select[name='periode']").find(":selected").val();
        break;
        case 'month':
            let periode1 = $("select[name='periode[0]']").find(":selected").val();
            let periode2 = $("select[name='periode[1]']").find(":selected").val();
            periode = periode1 + '-' + (periode2 < 10 ? '0' + periode2 : periode2);
        break;
    }
    updateChart(periode);
});
function updateChart(periode) {
    axios.post(domain + '/ajax/admin', {
        action: 'chart-users-regis', periode: periode
    })
    .then(res => {
        chartUserRegis.data.labels = res.data.labels;
        chartUserRegis.data.datasets[0].data = res.data.data;
        chartUserRegis.update();
    })
    .catch(err => {
        errorMessage('Update chart failed');
    });
}
// ---------- Chartjs end ---------- //

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

    changePeriodeType('chart-user-regis');
    updateChart(curr_year);
});
</script>
@endpush
