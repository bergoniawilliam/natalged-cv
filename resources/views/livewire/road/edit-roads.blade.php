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

    <h2 class="text-xl font-bold mb-5">Edit Roads</h2>

    <form wire:submit.prevent="update">

        <div class="mb-4">
            <label class="block mb-1 font-medium">Road Name</label>
            <input 
                type="text" 
                wire:model="Road_name"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                placeholder="Enter road name"
            >
            @error('Road_name') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>
         <div class="mb-4">
            <label class="block mb-1 font-medium">Road Address</label>
            <input 
                type="text" 
                wire:model="roadAddress"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                placeholder="Enter road address"
            >
            @error('Road_name')  
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
                href="{{ route('Roads') }}" 
                variant="outline"
            >
                Cancel
            </flux:button>

            <flux:button 
                type="submit" 
                variant="primary" 
                color="blue"
            >
                Update Road
            </flux:button>

        </div>

    </form>
</div>