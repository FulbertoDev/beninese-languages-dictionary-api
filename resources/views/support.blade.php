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
    @livewireStyles

</head>
<body class="font-sans antialiased h-screen w-screen bg-white dark:bg-gray-800">
<div class="md:w-1/2 mx-auto overflow-y-scroll h-full p-8">
    @isset($decrypted)
        <livewire:payment-form :data="$decrypted"/>
    @else
        <div class="flex items-center justify-center h-full flex-col space-y-4">
            <img class="h-16" src="{{\Illuminate\Support\Facades\URL::asset('images/logo.png')}}" alt="">
            <p class="text-center text-gray-900 dark:text-white text-base font-medium">
                Veuillez ouvrir votre application, et commencer le processus sur la page de soutien.
            </p>
        </div>
    @endif
</div>
<x-toaster-hub/>

@vite('resources/js/app.js')
@livewireScripts

</body>
</html>
