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
<div class="md:w-1/2 mx-auto overflow-y-auto h-full p-8">
    <livewire:payment-form/>
</div>
<x-toaster-hub/>

@vite('resources/js/app.js')
@livewireScripts

</body>
</html>
