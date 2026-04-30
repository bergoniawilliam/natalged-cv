<div class="p-6 max-w-xl mx-auto bg-white shadow rounded">

    {{-- SUCCESS MESSAGE --}}
    @if(session()->has('message'))
        <div class="mb-4 px-4 py-3 rounded border border-green-400 bg-green-500 text-white font-semibold shadow">
            ✔ {{ session('message') }}
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded border border-red-400 bg-red-500 text-white font-semibold shadow">
            ✖ Please complete all required fields.
        </div>
    @endif

    <h2 class="text-xl font-bold mb-5">Edit Relation</h2>

    <form wire:submit.prevent="update">

        {{-- BRIDGE --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium">Bridge Selection</label>

            <select
                wire:model.live="bridge_id"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
            >
                <option value="">Select Bridge</option>

                @foreach($bridges as $bridge)
                    <option value="{{ $bridge['id'] }}">
                        {{ $bridge['label'] }}
                    </option>
                @endforeach
            </select>

            @error('bridge_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- AFFECTED TYPE --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium">Affected Type</label>

            <select
                wire:model.live="affected_type"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
            >
                <option value="">Select Type</option>
                <option value="AffectedRoads">Roads</option>
                <option value="AffectedEvacuationCenter">Evacuation Center</option>
            </select>

            {{-- LOADING --}}
            <div wire:loading wire:target="affected_type" class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Loading...
            </div>

            @error('affected_type')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- AFFECTED NAME --}}
        <div class="mb-4">
            <label class="block mb-1 font-medium">Affected Name</label>

            <select
                wire:model.live="affected_id"
                class="w-full border rounded px-3 py-2"
            >
                <option value="">Select Item</option>

                @foreach($affectedItems as $item)
                    <option value="{{ $item['id'] }}">
                        {{ $item['name'] }}
                    </option>
                @endforeach
            </select>

            {{-- LOADING --}}
            <div wire:loading wire:target="affected_id" class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Fetching...
            </div>

            @error('affected_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- LAT / LONG --}}
        <div class="grid grid-cols-2 gap-3 mt-3">
            <div class="bg-blue-50 p-3 rounded">
                Latitude<br><strong>{{ $latitude }}</strong>
            </div>

            <div class="bg-green-50 p-3 rounded">
                Longitude<br><strong>{{ $longtitude }}</strong>
            </div>
        </div>

        {{-- MINI MAP --}}
        @if($latitude && $longtitude)
            <div class="mt-4 border rounded overflow-hidden">
                <iframe
                    width="100%"
                    height="200"
                    frameborder="0"
                    style="border:0"
                    src="https://www.google.com/maps?q={{ $latitude }},{{ $longtitude }}&z=16&output=embed"
                    allowfullscreen>
                </iframe>
            </div>
        @endif

        {{-- BUTTONS --}}
        <div class="flex justify-end gap-2 mt-5">

            <flux:button
                href="{{ route('Relation') }}"
                variant="outline"
            >
                Cancel
            </flux:button>

            <flux:button
                type="submit"
                variant="primary"
                color="blue"
            >
                Update Relation
            </flux:button>

        </div>

    </form>

</div>