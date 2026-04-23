<div class="max-w-2xl mx-auto p-4 space-y-4">

    <!-- Collection selector (readonly para di mabago collection) -->
    <div>
        <label class="font-bold mr-2">Collection:</label>
        <input type="text" wire:model="selectedCollection" class="border px-2 py-1 rounded bg-gray-100" readonly>
    </div>

    <form wire:submit.prevent="updateUser" class="space-y-3">

        <div class="flex flex-wrap -mx-2 gap-4">

            <div class="w-1/3 px-2">
                <label class="font-bold">BWC</label>
                <input type="text" wire:model="BWC" class="border p-2 w-full">
                @error('BWC') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="w-1/3 px-2">
                <label class="font-bold">CallSign</label>
                <input type="text" wire:model="CallSign" class="border p-2 w-full">
                @error('CallSign') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="w-1/3 px-2">
                <label class="font-bold">ContactNo</label>
                <input type="text" wire:model="ContactNo" class="border p-2 w-full">
                @error('ContactNo') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="w-1/3 px-2">
                <label class="font-bold">Name</label>
                <input type="text" wire:model="Name" class="border p-2 w-full">
                @error('Name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="w-1/3 px-2">
                <label class="font-bold">Payslip</label>
                <input type="text" wire:model="Payslip" class="border p-2 w-full">
                @error('Payslip') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="w-1/3 px-2">
                <label class="font-bold">Rank</label>
                <input type="text" wire:model="Rank" class="border p-2 w-full">
                @error('Rank') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="w-1/3 px-2">
                <label class="font-bold">Role</label>
                <input type="text" wire:model="Role" class="border p-2 w-full">
                @error('Role') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="w-1/3 px-2">
                <label class="font-bold">Station</label>
                <input type="text" wire:model="Station" class="border p-2 w-full">
                @error('Station') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="w-1/3 px-2">
                <label class="font-bold">SubUnit</label>
                <input type="text" wire:model="SubUnit" class="border p-2 w-full">
                @error('SubUnit') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="w-1/3 px-2">
                <label class="font-bold">Unit</label>
                <input type="text" wire:model="Unit" class="border p-2 w-full">
                @error('Unit') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="w-1/3 px-2">
                <label class="font-bold">Email</label>
                <input type="email" wire:model="email" class="border p-2 w-full">
                @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

        </div>

        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">
            Update User
        </button>
    </form>

    @if (session()->has('message'))
        <div class="mt-3 text-green-500 font-bold">
            {{ session('message') }}
        </div>
    @endif
</div>