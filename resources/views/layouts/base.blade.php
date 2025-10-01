<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @hasSection('title')

            <title>@yield('title') - {{ config('app.name') }}</title>
        @else
            <title>{{ config('app.name') }}</title>
        @endif

        <!-- Favicon -->
		<link rel="shortcut icon" href="{{ url(asset('favicon.ico')) }}">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @livewireScripts
        @fluxAppearance

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
    
            <flux:brand href="#" logo="https://fluxui.dev/img/demo/logo.png" name="Acme Inc." class="max-lg:hidden dark:hidden" />
            <flux:brand href="#" logo="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Acme Inc." class="max-lg:hidden! hidden dark:flex" />
    
            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="home" href="{{ route('home') }}" current>Home</flux:navbar.item>
                <flux:navbar.item icon="inbox" badge="12" href="{{ route('food.index') }}">Food</flux:navbar.item>
                
                <flux:separator vertical variant="subtle" class="my-2"/>
                <flux:navbar.item icon="inbox" badge="12" href="{{ route('favorite.index') }}">Favorite Foods</flux:navbar.item>
    
            </flux:navbar>
    
            <flux:spacer />
    
            <flux:navbar class="me-4">
                <flux:navbar.item icon="magnifying-glass" href="#" label="Search" />
                <flux:navbar.item class="max-lg:hidden" icon="cog-6-tooth" href="#" label="Settings" />
                <flux:navbar.item class="max-lg:hidden" icon="information-circle" href="#" label="Help" />
            </flux:navbar>
    
            @auth
                <flux:text>Hello, {{ auth()->user()->name }}</flux:text>
            @endauth
            <flux:dropdown position="top" align="start">
            @auth
                <flux:profile avatar="https://fluxui.dev/img/demo/user.png" href="" />

                <flux:menu>
                    <flux:menu.radio.group>
                        <flux:menu.radio checked>Profile</flux:menu.radio>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            @endauth
            @guest
                <flux:text>Hello, Guest</flux:text>
                <flux:dropdown position="top" align="start">
                    <flux:profile avatar="https://ui-avatars.com/api/?name=G&background=cccccc&color=ffffff" href="" />
                    <flux:menu>
                        <flux:menu.item icon="arrow-right-start-on-rectangle" href="{{ route('login') }}">Login</flux:menu.item>
                        <flux:menu.item icon="arrow-right-start-on-rectangle" href="{{ route('register') }}">Register</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            @endguest
            </flux:dropdown>
        </flux:header>
    
        <flux:sidebar sticky collapsible="mobile" class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.header>
                <flux:sidebar.brand
                    href="#"
                    logo="https://fluxui.dev/img/demo/logo.png"
                    logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png"
                    name="Acme Inc."
                />
    
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>
    
            <flux:sidebar.nav>
                <flux:sidebar.item icon="home" href="{{ route('home') }}">Home</flux:sidebar.item>
                <flux:sidebar.item icon="home" href="{{ route('food.index') }}">Food</flux:sidebar.item>
                <flux:sidebar.item icon="home" href="{{ route('favorite.index') }}">Favorite Foods</flux:sidebar.item>
            </flux:sidebar.nav>
    
            <flux:sidebar.spacer />
    
            <flux:sidebar.nav>
                <flux:sidebar.item icon="cog-6-tooth" href="#">Settings</flux:sidebar.item>
                <flux:sidebar.item icon="information-circle" href="#">Help</flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>
    
        <flux:main container>
            @hasSection('body')
                @yield('body')
            @elseif (isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </flux:main>
    
        @fluxScripts
    </body>
</html>
