<div class="p-6 max-w-xl mx-auto bg-white shadow rounded">

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

    <h2 class="text-xl font-bold mb-5">Add Evacuation</h2>

    <form wire:submit.prevent="save">

   
        <div class="mb-4">
            <label class="block mb-1 font-medium">Evacuation Name</label>
            <input 
                type="text" 
                wire:model="Evac_name"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                placeholder="Enter Evacuation name"
            >
            @error('Evac_name')  
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Address</label>
            <input 
                type="text" 
                wire:model="Address"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                placeholder="Enter Address"
            >
            @error('Address')  
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Capacity</label>
            <input 
                type="text" 
                wire:model="capacity"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                placeholder="Enter Capacity"
            >
            @error('capacity')  
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Latitude</label>
            <input 
                type="text" 
                wire:model="latitude"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                placeholder="Enter latitude name"
            >
            @error('Latitude') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Longtitude</label>
            <input 
                type="text" 
                wire:model="longtitude"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                placeholder="Enter longtitude name"
            >
            @error('longtitude') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>

       

        {{-- BUTTONS --}}
        <div class="flex justify-end gap-2 mt-5">

            <flux:button 
                href="{{ route('Evacuation') }}" 
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