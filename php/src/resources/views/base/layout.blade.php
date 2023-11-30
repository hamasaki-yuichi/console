<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css"/>
      <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @yield('css')

    <script src="{{ mix('js/app.js') }}" defer></script>
    @yield('javascript')
</head>
<body>
    @include('base/header')
    <main>
        @include('base/sidebar')
        <div>
            @yield('content')
            @include('base/footer')
        </div>
    </main>
</div>
</body>
</html>
