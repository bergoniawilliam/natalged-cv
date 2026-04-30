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

    <h2 class="text-xl font-bold mb-5">Add Affected Bridge</h2>

    <form wire:submit.prevent="save">

        {{-- BRIDGE NAME --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium">Bridge Name</label>
            <input type="text"
                wire:model="bridgename"
                class="w-full border rounded px-3 py-2"
                placeholder="Enter bridge name">
            @error('bridgename')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- AGE --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium">Bridge Age</label>
            <input type="text"
                wire:model="bridgeAge"
                class="w-full border rounded px-3 py-2"
                placeholder="Enter bridge age">
            @error('bridgeAge')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- LENGTH --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium">Bridge Length</label>
            <input type="text"
                wire:model="bridgeLength"
                class="w-full border rounded px-3 py-2"
                placeholder="Enter bridge length">
            @error('bridgeLength')
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

            <flux:button href="{{ route('affected-bridge') }}" variant="outline">
                Cancel
            </flux:button>

            <flux:button type="submit" variant="primary" color="blue">
                Save Bridge
            </flux:button>

        </div>

    </form>
</div>