<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-mode="dark" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{config('app.name')}}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite('resources/css/icons.css')
    @vite('resources/css/tailwind.css')
    @vite('resources/css/app.css')
    @livewireStyles

    <style>
        .custom-bg-gradient-to-r {
            background-image: -webkit-gradient(linear, left top, right top, from(var(--tw-gradient-stops))) !important;
            background-image: linear-gradient(to right, var(--tw-gradient-stops)) !important;
        }

        .custom-from-purple-900 {
            --tw-gradient-from: #581c87 !important;
            --tw-gradient-to: rgb(88 28 135 / 0) !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important;
        }

        .custom-to-purple-800 {
            --tw-gradient-to: #6b21a8 !important;
        }
    </style>
</head>
<body>

<!-- Navbar Start -->
<div class="navbar-custom" id="navbar">
    <nav>
        <div class="flex justify-center items-center lg:container">
            <a href="{{route('home')}}" class="flex ml-8 lg:ml-0">
                <span
                    class="self-center text-xl font-bold tracking-widest text-white uppercase font-poppins whitespace-nowrap">
                    {{config('app.name')}}
                </span>
            </a>
        </div>
    </nav>
</div>


<section class="py-32 lg:pb-40 lg:pt-56" id="home">
    <div class="overflow-hidden">
        <img class="absolute inset-0 object-cover w-full h-full" src="{{asset('images/bg-home.png')}}"
             alt="build your website image">
        <div
            class="absolute inset-0 w-full h-full custom-bg-gradient-to-r custom-from-purple-900 custom-to-purple-800 opacity-90"></div>
    </div>
    <div class="container">
        <div class="flex justify-center">
            <div class="relative grid items-center grid-cols-1 lg:grid-cols-12">
                <div class="col-span-8 lg:pr-10">
                    <div class="mb-10 space-y-6">
                        <!-- Home Page Title -->
                        <h2 class="text-white md:text-[32px] lg:text-[46px] leading-[64px] capitalize">
                            Le Dictionnaire<br>des Langues Béninoises
                        </h2>
                        <p class="text-white text-lg">
                            Une application dédiée à la promotion et à la digitalisation des langues béninoises.<br>C’est
                            le 1er dictionnaire mobile pour explorer les langues du Bénin avec +20.000 mots et
                            expressions
                        </p>

                        <a href="{{route('support.direct')}}" type="button"
                           class="text-white bg-primary btn hover:text-white">
                            Soutenir <i class="mdi mdi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-span-4">
                    <div class="bg-gray-100 rounded-lg p-4 text-gray-900">
                        <livewire:payment-form :action="$action"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<x-toaster-hub/>

@vite('resources/js/app.js')
@vite('resources/js/smooth-scroll.polyfills.min.js')
@vite('resources/js/gumshoe.polyfills.min.js')
@livewireScripts

</body>
</html>
