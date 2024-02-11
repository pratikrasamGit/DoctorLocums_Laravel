<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('inc.head')
</head>
<body>
<!-- Wrapper -->
<div id="wrapper" class="main-bg" style="{{$back_big}}">
@yield('content')
</div>
<!-- Wrapper / End -->
<!-- Scripts
================================================== -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/jquery-migrate-3.1.0.min.js') }}"></script>
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
$('.selectform').select2();
});
</script>
<script type="text/javascript" id="pap_x2s6df8d" src="https://nurseify.postaffiliatepro.com/scripts/76jow0"></script>
<script type="text/javascript">
PostAffTracker.setAccountId('default1');
try {
PostAffTracker.track();
} catch (err) { }
</script>
@yield('captcha')
</body>
</html>