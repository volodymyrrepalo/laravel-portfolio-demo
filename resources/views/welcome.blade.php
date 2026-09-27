<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @fonts

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a]">
        @php
            $mushrooms = [
                [
                    'name' => 'Golden Chanterelle',
                    'scientificName' => 'Cantharellus cibarius',
                    'summary' => 'A golden, funnel-shaped mushroom that forms mycorrhizal relationships with hardwood and conifer trees.',
                    'imageUrl' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Cantharellus%20cibarius%20149403743.jpg?width=1200',
                    'imageAlt' => 'Golden chanterelle mushrooms growing among moss and leaf litter.',
                    'wikipediaUrl' => 'https://en.wikipedia.org/wiki/Cantharellus_cibarius',
                    'creditUrl' => 'https://commons.wikimedia.org/wiki/File:Cantharellus_cibarius_149403743.jpg',
                    'creditLabel' => 'Photo by muukalainen · CC0 via Wikimedia Commons',
                ],
                [
                    'name' => 'Fly Agaric',
                    'scientificName' => 'Amanita muscaria',
                    'summary' => 'A distinctive red-capped mushroom with white spots, associated with temperate and boreal forests.',
                    'imageUrl' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Amanita%20muscaria%20mushroom.jpg?width=1200',
                    'imageAlt' => 'A red fly agaric mushroom with white spots growing in grass.',
                    'wikipediaUrl' => 'https://en.wikipedia.org/wiki/Amanita_muscaria',
                    'creditUrl' => 'https://commons.wikimedia.org/wiki/File:Amanita_muscaria_mushroom.jpg',
                    'creditLabel' => 'Photo by Paolo Neo · Public domain via Wikimedia Commons',
                ],
                [
                    'name' => 'Common Morel',
                    'scientificName' => 'Morchella esculenta',
                    'summary' => 'A honeycombed, spring-fruiting mushroom often found in temperate woodland habitats.',
                    'imageUrl' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Morchella%20esculenta%2070269680.jpg?width=1200',
                    'imageAlt' => 'A common morel mushroom with a honeycombed cap growing in woodland leaf litter.',
                    'wikipediaUrl' => 'https://en.wikipedia.org/wiki/Morchella_esculenta',
                    'creditUrl' => 'https://commons.wikimedia.org/wiki/File:Morchella_esculenta_70269680.jpg',
                    'creditLabel' => 'Photo by jlallaman · CC0 via Wikimedia Commons',
                ],
            ];
        @endphp

        <x-site-header active="home" />

        <x-mushroom-carousel :mushrooms="$mushrooms" class="mb-16" />

        <x-mobile-navigation-footer />
    </body>
</html>
