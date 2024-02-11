<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
@include('inc.admin.head')
</head>
<body class="gray inner-page">
<!-- Wrapper -->
<div id="wrapper">
@include('inc.admin.header')
<!-- Dashboard Container -->
<div class="dashboard-container">
@include('inc.admin.sidebar')
	<!-- Dashboard Content
	================================================== -->
	<div class="dashboard-content-container" data-simplebar>
		<div class="dashboard-content-inner" >	
		@include('inc.messages')
		@yield('content')
        @include('inc.admin.footer')