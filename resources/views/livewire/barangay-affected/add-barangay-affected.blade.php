<div class="p-6 max-w-xl mx-auto bg-white shadow rounded">

    {{-- SUCCESS --}}
    @if(session()->has('message'))
        <div class="mb-4 px-4 py-3 rounded border border-green-400 bg-green-500 text-white font-semibold shadow">
            ✔ {{ session('message') }}
        </div>
    @endif

    {{-- ERROR --}}
    @if(session()->has('error'))
        <div class="mb-4 px-4 py-3 rounded border border-red-400 bg-red-500 text-white font-semibold shadow">
            ✖ {{ session('error') }}
        </div>
    @endif

    <h2 class="text-xl font-bold mb-5">Add Affected Barangay</h2>

    <form wire:submit.prevent="save">

        {{-- BARANGAY NAME --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium">Barangay Name</label>
            <input type="text"
                wire:model="barangayName"
                class="w-full border rounded px-3 py-2"
                placeholder="Enter barangay name">
            @error('barangayName')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- FAMILIES --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium">Families Affected</label>
            <input type="text"
                wire:model="familiesAffected"
                class="w-full border rounded px-3 py-2"
                placeholder="Enter number of families">
            @error('familiesAffected')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- LATITUDE --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium">Latitude</label>
            <input type="text"
                wire:model="latitude"
                class="w-full border rounded px-3 py-2"
                placeholder="Enter latitude">
            @error('latitude')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- LONGITUDE --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium">Longitude</label>
            <input type="text"
                wire:model="longtitude"
                class="w-full border rounded px-3 py-2"
                placeholder="Enter longitude">
            @error('longtitude')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- BUTTONS --}}
        <div class="flex justify-end gap-2 mt-5">

            <flux:button 
                href="{{ route('barangay-affected') }}" 
                variant="outline"
            >
                Cancel
            </flux:button>

            <flux:button 
                type="submit" 
                variant="primary" 
                color="blue"
            >
                Save Barangay
            </flux:button>

        </div>

    </form>
</div>