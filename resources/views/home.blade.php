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
    <section>
        <div class="container">
            <div class="w-100 center-between gap-3 mb-4">
                <h3>Title</h3>
                <div class="input-group max-w-320px">
                    <input type="text" name="search" class="form-control form-control-sm">
                    <button class="btn btn-outline-secondary" type="button" id="button-addon2"><i class='bx bx-search' ></i></button>
                </div>
            </div>
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
    $('#link-dashboard-home').css({'font-weight': 600, 'color': '#ddd'});
});
</script>
@endpush
