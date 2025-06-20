<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{config('app.name')}}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite('resources/css/app.css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,400..700;1,400..700&display=swap');

        * {
            font-family: 'Work Sans', system-ui, sans-serif;
        }

        body {
            overflow: hidden;
        }
    </style>
</head>
<body class="font-sans antialiased h-screen w-screen bg-red-400 dark:bg-gray-800">
<div class="h-screen flex flex-col items-center justify-center align-middle space-y-6">
    <img class="h-32" src="{{asset("images/logo.png")}}" alt="">
    <span class="text-xl font-bold text-gray-900 dark:text-white">Merci pour votre soutien !</span>
    <span class="text-gray-900 dark:text-white text-center">Vous pouvez fermer et relancer à nouveau votre
            application pour bénéficier
            de tous les
            mots diponibles.</span>
</div>

</body>
</html>
