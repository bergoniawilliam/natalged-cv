<div class="flex h-full w-full flex-1 flex-col gap-4">

    {{-- Page Header --}}
    <div class="flex items-center justify-between w-full">
        <div>
            <h1 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">Patrollers / Commanders</h1>
            <p class="text-xs text-neutral-400 mt-0.5">Manage personnel records across all units</p>
        </div>
        {{-- Replace the old wire:navigate button --}}
        <div class="flex-shrink-0 ml-auto">
            <flux:modal.trigger name="add-user-modal">
                <flux:button variant="primary" icon="plus">
                    Add User
                </flux:button>
            </flux:modal.trigger>
        </div>

        <flux:modal name="add-user-modal" class="w-full max-w-3xl">
            <div class="space-y-6">

                {{-- Header --}}
                <div class="flex items-start gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-950/40 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                    </div>
                    <div>
                        <flux:heading size="lg">Add User</flux:heading>
                        <flux:text class="mt-0.5 text-xs text-neutral-400">Fill in the details to create a new personnel record.</flux:text>
                    </div>
                </div>

                @if(session()->has('error'))
                    <div class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Collection Selector --}}
                <div>
                    <flux:label class="mb-1.5 text-xs font-medium">Collection</flux:label>
                    <select
                        wire:model="selectedCollection"
                        @disabled($isLocked)
                        class="w-full text-sm border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-800 dark:text-neutral-100 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        @foreach($firebase_collections as $col)
                            <option value="{{ $col }}">{{ $col }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Divider --}}
                <div class="border-t border-neutral-100 dark:border-neutral-800"></div>

                {{-- Form Fields --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <flux:label class="mb-1.5 text-xs font-medium">Name</flux:label>
                        <flux:input wire:model="Name" placeholder="Full name" />
                        @error('Name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <flux:label class="mb-1.5 text-xs font-medium">Rank</flux:label>
                        <flux:input wire:model="Rank" placeholder="e.g. PCpl" />
                        @error('Rank') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <flux:label class="mb-1.5 text-xs font-medium">Role</flux:label>
                        <flux:input wire:model="Role" placeholder="e.g. Patroller" />
                        @error('Role') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <flux:label class="mb-1.5 text-xs font-medium">Email</flux:label>
                        <flux:input type="email" wire:model="email" placeholder="email@example.com" />
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <flux:label class="mb-1.5 text-xs font-medium">Password</flux:label>
                        <flux:input type="password" wire:model="password" placeholder="Min. 8 characters" />
                        @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <flux:label class="mb-1.5 text-xs font-medium">Contact No</flux:label>
                        <flux:input wire:model="ContactNo" placeholder="09XXXXXXXXX" />
                        @error('ContactNo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <flux:label class="mb-1.5 text-xs font-medium">Unit</flux:label>
                        <flux:input wire:model="Unit" placeholder="e.g. PRO 2" />
                        @error('Unit') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <flux:label class="mb-1.5 text-xs font-medium">SubUnit</flux:label>
                        <flux:input wire:model="SubUnit" placeholder="e.g. NVPPO" />
                        @error('SubUnit') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <flux:label class="mb-1.5 text-xs font-medium">Station</flux:label>
                        <flux:input wire:model="Station" placeholder="e.g. Ambaguio PS" />
                        @error('Station') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <flux:label class="mb-1.5 text-xs font-medium">Call Sign</flux:label>
                        <flux:input wire:model="CallSign" placeholder="e.g. BRAVO-1" />
                        @error('CallSign') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <flux:label class="mb-1.5 text-xs font-medium">Payslip</flux:label>
                        <flux:input wire:model="Payslip" placeholder="Payslip number" />
                        @error('Payslip') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <flux:label class="mb-1.5 text-xs font-medium">BWC</flux:label>
                        <flux:input wire:model="BWC" placeholder="BWC serial" />
                        @error('BWC') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-2 pt-4 border-t border-neutral-100 dark:border-neutral-800">
                    <flux:modal.close>
                        <flux:button variant="ghost" size="sm" icon="x-mark">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button
                        variant="primary"
                        size="sm"
                        icon="user-plus"
                        wire:click="saveUser"
                        wire:loading.attr="disabled"
                        wire:target="saveUser"
                    >
                        <span wire:loading.remove wire:target="saveUser">Save User</span>
                        <span wire:loading wire:target="saveUser">Saving...</span>
                    </flux:button>
                </div>

            </div>
        </flux:modal>
    </div>

    {{-- Filters Bar --}}
    <div class="flex flex-wrap gap-3 items-center rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-4 py-3 shadow-sm">
        <div class="flex items-center gap-2">
            <label class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Collection</label>
            <select
                wire:model.live="selectedCollection"
                @disabled($isLocked)
                class="text-sm border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-800 dark:text-neutral-100 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                @foreach($firebase_collections as $col)
                    <option value="{{ $col }}">{{ $col }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 min-w-[200px]">
            <input
                type="text"
                wire:model.live.debounce.500ms="query"
                placeholder="Search by name..."
                class="w-full text-sm border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-800 dark:text-neutral-100 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>

        <div class="flex items-center gap-2 ml-auto">
            <label class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Per Page</label>
            <select
                wire:model.live="perPage"
                class="text-sm border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-800 dark:text-neutral-100 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>

    {{-- Alerts --}}
    <div wire:loading wire:target="selectedCollection, query" class="text-xs text-blue-500 font-medium px-1">
        Loading users...
    </div>

    @if(session()->has('message'))
        <div class="rounded-lg bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800 px-4 py-2 text-sm text-green-700 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="rounded-lg bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 px-4 py-2 text-sm text-red-700 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

        {{-- Table --}}
        <div class="w-full rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-sm"
        x-data
        x-on:showAlert.window="alert($event.detail)">

        <div class="overflow-x-auto w-full">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-200 dark:border-neutral-700">
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 whitespace-nowrap">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 whitespace-nowrap">Rank/Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 whitespace-nowrap">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 whitespace-nowrap">Unit Assignment</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 whitespace-nowrap">Call Sign</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 whitespace-nowrap">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 whitespace-nowrap">Contact</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 whitespace-nowrap">Payslip</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                    @forelse($this->collections as $index => $collection)
                        @php
                            // Combined Personnel
                            $rank = $collection['Rank'] ?? null;
                            $name = $collection['Name'] ?? null;
                            $personnel = collect([$rank, $name])->filter()->implode(' ');

                            // Combined Assignment
                            $station  = $collection['Station'] ?? null;
                            $subunit  = $collection['SubUnit'] ?? null;
                            $unit     = $collection['Unit'] ?? null;
                            $assignment = collect([$station, $subunit, $unit])->filter()->implode(', ');
                        @endphp
                        <tr class="group hover:bg-zinc-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-4 py-3 text-xs text-neutral-400 tabular-nums whitespace-nowrap">
                                {{ $this->collections->firstItem() + $index }}
                            </td>

                            {{-- Personnel: Rank + Name --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-medium text-neutral-900 dark:text-neutral-100">
                                    {{ $personnel ?: 'NA' }}
                                </span>
                            </td>

                            {{-- Email --}}
                            <td class="px-4 py-3 text-sm text-neutral-500 dark:text-neutral-400 whitespace-nowrap">
                                {{ $collection['email'] ?? 'NA' }}
                            </td>

                            {{-- Assignment: Station, SubUnit, Unit --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($assignment)
                                    <span class="text-sm text-neutral-700 dark:text-neutral-200">
                                        {{ $assignment }}
                                    </span>
                                @else
                                    <span class="text-sm text-neutral-400">NA</span>
                                @endif
                            </td>

                            {{-- Call Sign --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-950/40 px-2 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-300">
                                    {{ $collection['CallSign'] ?? 'NA' }}
                                </span>
                            </td>

                            {{-- Role --}}
                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-300 whitespace-nowrap">
                                {{ $collection['Role'] ?? 'NA' }}
                            </td>

                            {{-- Contact --}}
                            <td class="px-4 py-3 text-sm text-neutral-500 dark:text-neutral-400 whitespace-nowrap">
                                {{ $collection['ContactNo'] ?? 'NA' }}
                            </td>

                            {{-- Payslip --}}
                            <td class="px-4 py-3 text-sm font-medium text-neutral-700 dark:text-neutral-200 tabular-nums whitespace-nowrap">
                                {{ $collection['Payslip'] ?? 'NA' }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex gap-1 items-center opacity-70 group-hover:opacity-100 transition-opacity">

                                    {{-- EDIT --}}
                                    <flux:tooltip content="Edit User">
                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            wire:navigate
                                            href="{{ route('edituser', ['uid' => $collection['_id'], 'collection' => $selectedCollection]) }}"
                                            icon="pencil-square"
                                        />
                                    </flux:tooltip>

                                    {{-- DELETE --}}
                                    <flux:modal.trigger name="delete-user-modal">
                                        <flux:tooltip content="Delete User">
                                            <flux:button
                                                size="sm"
                                                variant="ghost"
                                                class="text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30"
                                                icon="trash"
                                                wire:click="setDeleteId('{{ $collection['_id'] }}')"
                                            />
                                        </flux:tooltip>
                                    </flux:modal.trigger>

                                    {{-- CHANGE PASSWORD --}}
                                   <flux:tooltip content="Change Password">
                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            icon="key"
                                            wire:click="openChangePasswordModal(
                                                '{{ $collection['_id'] }}',
                                                '{{ $collection['Name'] ?? '' }}',
                                                '{{ $collection['Rank'] ?? '' }}'
                                            )"
                                        />
                                    </flux:tooltip>

                                </div>

                                {{-- Change Password Modal --}}
                                <flux:modal
                                    wire:model="showPasswordModal"
                                    class="w-full max-w-md"
                                >
                                    <div x-data x-on:close-password-modal.window="setTimeout(()=>{ $flux.modal.close() }, 2000)">
                                        <div class="space-y-6">

                                            <div class="flex items-start gap-3">
                                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-950/40 shrink-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 0 1 21.75 8.25Z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <flux:heading size="lg">Change Password</flux:heading>
                                                    <flux:text class="mt-0.5 text-xs text-neutral-400">
                                                        {{ trim(($selectedUserRank ?? '') . ' ' . ($selectedUserName ?? 'No Name')) }}
                                                        <span class="mx-1">&middot;</span>
                                                        <span class="font-mono">{{ $selectedUserId }}</span>
                                                    </flux:text>
                                                </div>
                                            </div>

                                            @if($message)
                                                <div class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm
                                                    {{ $messageType === 'success'
                                                        ? 'bg-green-50 dark:bg-green-950/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800'
                                                        : 'bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800' }}">
                                                    @if($messageType === 'success')
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                                        </svg>
                                                    @endif
                                                    {{ $message }}
                                                </div>
                                            @endif

                                            <div class="space-y-3">
                                                <div>
                                                    <flux:label class="mb-1.5 text-xs font-medium">New Password</flux:label>
                                                    <div x-data="{ show: false }" class="relative">
                                                        <input x-bind:type="show ? 'text' : 'password'" wire:model="newPassword" placeholder="Min. 8 characters"
                                                            class="w-full text-sm border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-800 dark:text-neutral-100 rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                        <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600">
                                                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                            </svg>
                                                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display:none">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div>
                                                    <flux:label class="mb-1.5 text-xs font-medium">Confirm Password</flux:label>
                                                    <div x-data="{ show: false }" class="relative">
                                                        <input x-bind:type="show ? 'text' : 'password'" wire:model="confirmPassword" placeholder="Re-enter password"
                                                            class="w-full text-sm border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-800 dark:text-neutral-100 rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                        <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600">
                                                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                            </svg>
                                                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display:none">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                @error('newPassword')
                                                    <p class="flex items-center gap-1 text-xs text-red-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div class="flex items-center justify-end gap-2 pt-2 border-t border-neutral-100 dark:border-neutral-800">
                                              <flux:modal.close>
                                                    <flux:button
                                                        variant="ghost"
                                                        size="sm"
                                                        wire:click="closeChangePasswordModal"
                                                    >
                                                        Cancel
                                                    </flux:button>
                                                </flux:modal.close>
                                                <flux:button
                                                    size="sm"
                                                    variant="primary"
                                                    wire:click="changePassword"
                                                    wire:loading.attr="disabled"
                                                    icon="key"
                                                >
                                                    Update Password
                                                </flux:button>
                                            </div>

                                        </div>
                                    </div>
                                </flux:modal>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-16 text-sm text-neutral-400">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-neutral-100 dark:border-neutral-800 px-4 py-3">
            {{ $this->collections->links() }}
        </div>

    </div>
    
    <!-- DELETE MODAL -->
    <flux:modal name="delete-user-modal" class="w-full max-w-md">
    
        <div class="space-y-5">

            {{-- Header --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-red-50 dark:bg-red-950/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>

                <div>
                    <flux:heading size="lg">Delete User</flux:heading>
                    <flux:text class="text-xs text-neutral-400">
                        This action cannot be undone.
                    </flux:text>
                </div>
            </div>

            {{-- Warning Box --}}
            <div class="p-3 rounded-lg bg-red-50 dark:bg-red-950/20 text-sm text-red-600 dark:text-red-300 border border-red-200 dark:border-red-800">
                Are you sure you want to delete this user record?
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-2 pt-2">
                
                <flux:modal.close>
                    <flux:button variant="ghost" size="sm">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="danger"
                    size="sm"
                    icon="trash"
                    wire:click="deleteUser"
                    wire:loading.attr="disabled"
                    wire:target="deleteUser"
                >
                    <span wire:loading.remove wire:target="deleteUser">Delete</span>
                    <span wire:loading wire:target="deleteUser">Deleting...</span>
                </flux:button>

            </div>

        </div>

    </flux:modal>
</div>