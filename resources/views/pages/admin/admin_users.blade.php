@extends('layouts.dashboard')

@push('css-styles')
<style>
#content-wrapper { padding: 2.75rem 2.25rem; }

@media (max-width: 1199px) {
}
</style>
@endpush

@section('content')

<div id="content-wrapper">
    <div class="center-between">
        <h5>Users Controller</h5>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb font-8em">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item">Admin</li>
              <li class="breadcrumb-item active" aria-current="page">Users Controller</li>
            </ol>
        </nav>
    </div>
    <section>
        <div class="container">
            <div class="row"></div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
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

$(document).ready(function() {
    $('#link-dashboard-admin').css({'font-weight': 600, 'color': '#ddd'});
    $('#submenu-admin').addClass('show');
    $('#link-dashboard-admin-users').css({'font-weight': 500, 'color': '#ddd'});
});
</script>
@endpush
