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

    <h2 class="text-xl font-bold mb-5">Add Bridge</h2>

    <form wire:submit.prevent="save">

        {{-- Bridge Name --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium">Bridge Name</label>
            <input 
                type="text" 
                wire:model="Bridge_name"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                placeholder="Enter Bridge name"
            >
            @error('Bridge_name') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>


        {{-- WaterLevel --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium">Water Level</label>
            <textarea 
                wire:model="Water_lvl"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                rows="3"
                placeholder="Enter WaterLevel"
            ></textarea>
        </div>

        {{-- BUTTONS --}}
        <div class="flex justify-end gap-2 mt-5">

            <flux:button 
                href="{{ route('RefBridgeWaterlevel') }}" 
                variant="outline"
            >
                Cancel
            </flux:button>

            <flux:button 
                type="submit" 
                variant="primary" 
                color="blue"
            >
                Save Bridge
            </flux:button>

        </div>

    </form>
</div>