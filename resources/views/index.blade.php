@extends('layouts.master')

@push('css-styles')
<style>
#section-intro h1, #section-intro h2, #section-intro h3, #section-intro h4, #section-intro h5, #section-intro p { font-family: var(--bs-font-sans-serif); }
#section-intro p { font-weight: 300 }

#section-intro {
    background: linear-gradient(45deg, rgba(1, 1, 1, .7), rgba(1, 1, 1, .9)), url("{{ asset('images/assets/photos/dekstop.jpg') }}");
    background-size: cover;
    color: #f1f1f1;
}

@media (max-width: 1199px) {
}
</style>
@endpush

@section('content')
<nav>
    <div class="w-100 text-light px-4 py-2" style="position: fixed">
        <div class="center-between">
            <a href="/"><b>Laravel</b> Foundation</a>
            <a href="https://github.com/Farabirza/Laravel-Foundation" class="center gap-2 font-8em" target="_blank"><i class='bx bxl-github'></i>Farabirza/Laravel-Foundation</a>
        </div>
    </div>
</nav>

<!-- section-intro -->
<section id="section-intro">
    <div class="vh-100">
        <div class="h-100 center">
            <div class="text-center max-w-1024px">
                <h3 class="">Welcome</h3>
                <p class="mt-3 max-w-576px">Pellentesque ut orci bibendum dolor fermentum rhoncus in sed risus. Nulla eget feugiat urna. Donec ornare leo a augue fermentum commodo.</p>
                <div class="mt-5 center gap-3">
                    <button class="btn btn-outline-light" onclick="showAuthModal('login')">Sign in</button>
                    <a class="btn btn-outline-light">About us</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- section-intro end -->

@include('components.modals.modalAuth')

@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function(){
});
</script>
@endpush
