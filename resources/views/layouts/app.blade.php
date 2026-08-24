<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Top Plans</title>
    @include('front.partial.csslink')
    @yield('css')
</head>

<body>
     @include('front.partial.header')
    @yield('content')
       @include('front.partial.chatwidget')
    @include('front.partial.footer')
    @include('front.partial.jslink')
    @yield('script')
</body>
</html>
