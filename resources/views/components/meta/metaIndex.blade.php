<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
<meta name="csrf-token" content="{{ csrf_token() }}">

@if(isset($meta_tags))
<meta name="title" content="{{$meta_tags['title'] ?? 'Laravel Foundation'}}" />
<meta name="description" content="{{$meta_tags['description'] ?? 'Buat undangan digitalmu sendiri!'}}" />
<meta name="keywords" lan="id" content="{{$meta_tags['keywords'] ?? 'Laravel Foundation, undangan digital, undangan pernikahan'}}" />
<meta property="og:title" content="{{$meta_tags['title'] ?? 'Laravel Foundation'}}" />
<meta property="og:type" content="website" />
<meta property="og:description" content="{{$meta_tags['description'] ?? 'Buat undangan digitalmu sendiri!'}}" />
<meta property="og:site_name" content="Laravel Foundation"/>
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:site_name" content="www.Laravel Foundation" />
<meta property="og:image" content="{{ $meta_tags['image'] ?? asset('images/assets/cover.jpg') }}" />
<meta property="og:image:type" content="image/jpg" />
<meta property="og:image:width" content="1366" />
<meta property="og:image:height" content="768" />
<meta name="twitter:card" content="summary"/>
<meta name="twitter:site" content="@ruangnamu"/>
<meta name="twitter:creator" content="@ruangnamu"/>
<meta name="twitter:title" content="{{$meta_tags['title'] ?? 'Laravel Foundation'}}"/>
<meta name="twitter:image" content="{{ $meta_tags['image'] ?? asset('images/assets/cover.jpg') }}" />

<title>{{$meta_tags['title'] ?? 'Laravel Foundation'}}</title>
@endif

<!-- Favicons -->
<link href="{{ asset('/images/assets/icons/envelope-1.png') }}" rel="icon">
<link href="{{ asset('/images/assets/icons/envelope-1.png') }}" rel="apple-touch-icon">


