<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Turnero</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900 ">
    <div class="max-w-3xl mx-auto p-6 justify-items-center">
        <h1 class="text-2xl font-bold mb-4">@yield('title', 'Turnero')</h1>
        @yield('content')
    </div>
</body>

</html>
