<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('inc.head')
    <script id="pap_x2s6df8d" src="https://nurseify.postaffiliatepro.com/scripts/76jow0" type="text/javascript"></script>
    <script type="text/javascript">
        PostAffTracker.setAccountId('default1');
        var sale = PostAffTracker.createAction('nursesignup');
        sale.setTotalCost('120.50');
        sale.setOrderID('NERSEIFY_123AFF');
        sale.setCampaignID('b3136b25');
        sale.setStatus('A');
        sale.setCustomCommission('5');
        PostAffTracker.register();
    </script>
</head>

<body class="gray inner-page">
    <!-- Wrapper -->
    <div id="wrapper">
        <!-- Header Container -->
        @include('inc.header')
        <!-- Header Container / End -->
        @yield('content')
        @include('inc.page.footer')
