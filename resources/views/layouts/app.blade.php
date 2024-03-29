<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Stripe -->
    <meta name="stripe-key" content="{{ env('STRIPE_KEY') }}">
    <title>bemail</title>
    <link rel="icon" type="image/png" href="/images/logo/favicon.png">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('layouts.app.navbar')
        <div class="container">
            @yield('content')
        </div>
    </div>
    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
