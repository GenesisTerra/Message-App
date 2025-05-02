<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style1.css') }}">
</head>

<body>
    @if(session('user_id') && (View::yieldContent('title') !== 'Setup Profile'))
    @include('partials.nav_pass')
    <div class="gridM" id="gridContainer">
        @include('partials.sidenav')
        <div class="main" id="main">@yield('content')</div>
    </div>
    @else
    @yield('content')
    @endif

    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>