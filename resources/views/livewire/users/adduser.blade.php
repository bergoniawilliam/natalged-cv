<div class="max-w-2xl mx-auto p-4 space-y-4">

    {{-- SUCCESS MESSAGE --}}
    @if(session()->has('message'))
        <div class="mb-4 px-4 py-3 rounded border border-green-400 bg-green-500 text-white font-semibold shadow">
            ✔ {{ session('message') }}
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if(session()->has('error'))
        <div class="mb-4 px-4 py-3 rounded border border-red-400 bg-red-500 text-white font-semibold shadow">
            ✖ {{ session('error') }}
        </div>
    @endif

    <h2 class="text-xl font-bold mb-5">Add User</h2>

    {{-- Collection --}}
    <div class="mb-4">
        <label class="font-bold">Collection</label>
        <select wire:model.live.debounce.500ms="selectedCollection" class="border px-2 py-2 rounded w-full">
            @foreach($firebase_collections as $col)
                <option value="{{ $col }}">{{ $col }}</option>
            @endforeach
        </select>
    </div>

    <form wire:submit.prevent="saveUser">

        <div class="flex flex-wrap -mx-2 gap-4">

            {{-- BWC --}}
            <div class="w-1/3 px-2">
                <label class="font-bold">BWC</label>
                <input type="text" wire:model="BWC" class="border p-2 w-full rounded">
                @error('BWC') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- CallSign --}}
            <div class="w-1/3 px-2">
                <label class="font-bold">CallSign</label>
                <input type="text" wire:model="CallSign" class="border p-2 w-full rounded">
                @error('CallSign') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- ContactNo --}}
            <div class="w-1/3 px-2">
                <label class="font-bold">ContactNo</label>
                <input type="text" wire:model="ContactNo" class="border p-2 w-full rounded">
                @error('ContactNo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Name --}}
            <div class="w-1/3 px-2">
                <label class="font-bold">Name</label>
                <input type="text" wire:model="Name" class="border p-2 w-full rounded">
                @error('Name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Payslip --}}
            <div class="w-1/3 px-2">
                <label class="font-bold">Payslip</label>
                <input type="text" wire:model="Payslip" class="border p-2 w-full rounded">
                @error('Payslip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Rank --}}
            <div class="w-1/3 px-2">
                <label class="font-bold">Rank</label>
                <input type="text" wire:model="Rank" class="border p-2 w-full rounded">
                @error('Rank') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Role --}}
            <div class="w-1/3 px-2">
                <label class="font-bold">Role</label>
                <input type="text" wire:model="Role" class="border p-2 w-full rounded">
                @error('Role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Station --}}
            <div class="w-1/3 px-2">
                <label class="font-bold">Station</label>
                <input type="text" wire:model="Station" class="border p-2 w-full rounded">
                @error('Station') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- SubUnit --}}
            <div class="w-1/3 px-2">
                <label class="font-bold">SubUnit</label>
                <input type="text" wire:model="SubUnit" class="border p-2 w-full rounded">
                @error('SubUnit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Unit --}}
            <div class="w-1/3 px-2">
                <label class="font-bold">Unit</label>
                <input type="text" wire:model="Unit" class="border p-2 w-full rounded">
                @error('Unit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Email --}}
            <div class="w-1/3 px-2">
                <label class="font-bold">Email</label>
                <input type="email" wire:model="email" class="border p-2 w-full rounded">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Password --}}
            <div class="w-1/3 px-2">
                <label class="font-bold">Password</label>
                <input type="password" wire:model="password" class="border p-2 w-full rounded">
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

        </div>

        {{-- BUTTONS --}}
        <div class="flex justify-end gap-2 mt-5">

             <flux:button 
                href="{{ route('users') }}" 
                variant="outline"
            >
                Cancel
            </flux:button>

            <flux:button 
                type="submit" 
                variant="primary" 
                color="blue"
            >
                Save
            </flux:button>
        </div>

    </form>

</div>