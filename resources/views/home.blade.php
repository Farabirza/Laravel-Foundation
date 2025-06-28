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
                <h3 class="page-title">Welcome, {{ auth()->user()->full_name }}</h3>
                <div class="input-group max-w-320px">
                    <input type="text" name="search" class="form-control form-control-sm">
                    <button class="btn btn-outline-secondary" type="button" id="button-addon2"><i class='bx bx-search' ></i></button>
                </div>
            </div>
            <div class="row">
                @for($i = 1; $i <= 16; $i++)
                <div class="col-md-3 p-3">
                    <div class="border border-2 rounded overflow-hidden">
                        {{-- <img src="{{ asset('images/assets/default.jpg') }}" alt="" class="img-fluid"> --}}
                        <img src="{{ 'https://picsum.photos/id/'.rand(1, 999).'/640/640' }}" alt="" class="img-fluid">
                        <div class="p-2 text-center">
                            <p class="m-0 font-9em">Card</p>
                            <p class="m-0 text-secondary font-8em">{{ cutWords(fake()->paragraph()) }}</p>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            <div class="w-100 center-end mt-4">
                <nav>
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link text-secondary" href="#">Previous</a></li>
                        <li class="page-item"><a class="page-link text-secondary" href="#">1</a></li>
                        <li class="page-item"><a class="page-link text-secondary" href="#">2</a></li>
                        <li class="page-item"><a class="page-link text-secondary" href="#">3</a></li>
                        <li class="page-item"><a class="page-link text-secondary" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
</div>
@endsection

@php
function cutWords(string $text, int $limit = 10): string {
    $words = explode(' ', trim($text));
    if (count($words) <= $limit) {
        return $text;
    }

    $sliced = array_slice($words, 0, $limit);
    return implode(' ', $sliced) . '...';
}
@endphp

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
