  <!-- Basic Page Needs
================================================== -->
<title>@yield('title')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if($favicon)
  <!-- Icons -->
  <link rel="shortcut icon" href="data:image/jpeg;base64,{{ base64_encode($favicon) }}">
  @endif
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <!-- CSS
================================================== -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @yield('header_css')