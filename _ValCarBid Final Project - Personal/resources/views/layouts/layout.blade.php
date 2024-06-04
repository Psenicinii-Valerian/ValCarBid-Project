<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href='{{ asset('scss/styles.css') }}'>
    @yield('doc_scripts')
    <title>@yield('doc_title')</title>
</head>

<body>
    <header>
        {{-- Profile Navbar Start --}}
            <div class="navbar-me">
                <x-app-layout>
                    <!-- Content of x-app-layout goes here -->
                </x-app-layout>
            </div>
        {{-- Profile Navbar End --}}
    </header>

    @yield('doc_body')

    <footer>
        <x-footer />
    </footer>
</body>

</html>
