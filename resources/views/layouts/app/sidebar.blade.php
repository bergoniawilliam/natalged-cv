<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-white dark:border-zinc-700/60 dark:bg-zinc-950">

            {{-- Logo --}}
            <flux:sidebar.header class="border-b border-zinc-100 dark:border-zinc-800 py-4 px-4">
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="px-2 py-3">

                <flux:sidebar.group :heading="__('Platform')" class="grid">

                    @can('dashboard.view')
                    <flux:sidebar.item icon="home"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    @endcan

                    @can('users.view')
                    <flux:sidebar.item icon="users"
                        :href="route('users')"
                        :current="request()->routeIs('users')"
                        wire:navigate>
                        {{ __('Patrollers / Commanders') }}
                    </flux:sidebar.item>
                    @endcan

                  

                </flux:sidebar.group>

                <flux:sidebar.group heading="" class="grid mt-1">

                    @can('relation.view')
                    <flux:sidebar.item icon="link"
                        :href="route('Relation')"
                        :current="request()->routeIs('Relation')"
                        wire:navigate>
                        {{ __('Relation') }}
                    </flux:sidebar.item>
                    @endcan

                    @can('bridges.view')
                    <flux:sidebar.item icon="building-library"
                        :href="route('bridges')"
                        :current="request()->routeIs('bridges')"
                        wire:navigate>
                        {{ __('Bridges') }}
                    </flux:sidebar.item>
                    @endcan

                    @can('bridges.view')
                    <flux:sidebar.item icon="chart-bar"
                        :href="route('RefBridgeWaterlevel')"
                        :current="request()->routeIs('RefBridgeWaterlevel')"
                        wire:navigate>
                        {{ __('Ref Bridge Waterlevel') }}
                    </flux:sidebar.item>
                    @endcan

                    @can('roads.view')
                    <flux:sidebar.item icon="map"
                        :href="route('Roads')"
                        :current="request()->routeIs('Roads')"
                        wire:navigate>
                        {{ __('Roads') }}
                    </flux:sidebar.item>
                    @endcan

                </flux:sidebar.group>

                <flux:sidebar.group heading="" class="grid mt-1">

                    @can('evacuation.view')
                    <flux:sidebar.item icon="home-modern"
                        :href="route('Evacuation')"
                        :current="request()->routeIs('Evacuation')"
                        wire:navigate>
                        {{ __('Evacuation') }}
                    </flux:sidebar.item>
                    @endcan

                    @can('affected-bridge.view')
                    <flux:sidebar.item icon="exclamation-triangle"
                        :href="route('affected-bridge')"
                        :current="request()->routeIs('affected-bridge')"
                        wire:navigate>
                        {{ __('Affected Bridge') }}
                    </flux:sidebar.item>
                    @endcan

                    @can('barangay-affected.view')
                    <flux:sidebar.item icon="map-pin"
                        :href="route('barangay-affected')"
                        :current="request()->routeIs('barangay-affected')"
                        wire:navigate>
                        {{ __('Affected Barangay') }}
                    </flux:sidebar.item>
                    @endcan

                </flux:sidebar.group>

            </flux:sidebar.nav>

            <flux:spacer />

            {{-- Bottom admin links --}}
            <div class="border-t border-zinc-100 dark:border-zinc-800 px-3 py-3 space-y-0.5">

                @can('admin.view')
                <a href="{{ route('admin.users') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Admin Accounts
                </a>
                @endcan

                @can('uac.view')
                <flux:sidebar.item icon="shield-check"
                    :href="route('uac.roles')"
                    wire:navigate>
                    UAC Panel
                </flux:sidebar.item>
                @endcan

            </div>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->first_name" />

        </flux:sidebar>

        {{-- Mobile header --}}
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />
                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->first_name"
                                    :initials="auth()->user()->initials()"
                                />
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->first_name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>
                    <flux:menu.item :href="route('uac.roles')" icon="shield-check" wire:navigate>
                        UAC Panel
                    </flux:menu.item>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>