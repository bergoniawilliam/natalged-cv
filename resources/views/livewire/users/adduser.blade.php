<div class="max-w-3xl mx-auto p-6">

    <flux:card class="p-6 space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <flux:heading size="lg">Add User</flux:heading>
        </div>

        {{-- SUCCESS --}}
        @if(session()->has('message'))
            <flux:callout variant="success">
                {{ session('message') }}
            </flux:callout>
        @endif

        {{-- ERROR --}}
        @if(session()->has('error'))
            <flux:callout variant="danger">
                {{ session('error') }}
            </flux:callout>
        @endif

        {{-- COLLECTION (READ ONLY) --}}
        <div class="space-y-1">
            <flux:label>Collection</flux:label>
            <flux:input
                value="{{ auth()->user()->collection }}"
                readonly
                class="bg-zinc-100 dark:bg-zinc-800"
            />
        </div>

        <form wire:submit.prevent="saveUser" class="space-y-6">

            {{-- GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <flux:input label="BWC" wire:model="BWC" />
                <flux:input label="CallSign" wire:model="CallSign" />
                <flux:input label="Contact No" wire:model="ContactNo" />

                <flux:input label="Name" wire:model="Name" />
                <flux:input label="Payslip" wire:model="Payslip" />
                <flux:input label="Rank" wire:model="Rank" />

                <flux:input label="Role" wire:model="Role" />
                <flux:input label="Station" wire:model="Station" />
                <flux:input label="SubUnit" wire:model="SubUnit" />

                <flux:input label="Unit" wire:model="Unit" />
                <flux:input type="email" label="Email" wire:model="email" />
                <flux:input type="password" label="Password" wire:model="password" />

            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-3 pt-4 border-t">

                <flux:button variant="ghost" href="{{ route('users') }}">
                    Cancel
                </flux:button>

                <flux:button type="submit" variant="primary" color="blue">
                    Save User
                </flux:button>

            </div>

        </form>

    </flux:card>

</div>