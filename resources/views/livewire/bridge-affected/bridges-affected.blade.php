<div>

    <h1 class="text-2xl font-bold mb-4">Affected Bridges</h1>

    {{-- FILTERS --}}
    <div class="flex flex-wrap gap-4 mb-4 items-center justify-between">

        <div class="flex flex-wrap gap-4 items-center">

            <div>
                <input type="text"
                    wire:model.live.debounce.500ms="query"
                    placeholder="Search bridge name..."
                    class="border px-3 py-1 rounded"
                >
            </div>

        </div>

        <div>
            <label class="font-bold mr-2">Per Page:</label>
            <select wire:model.live="perPage" class="border px-2 py-1 rounded">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        <div>
            <flux:button
                wire:navigate
                href="{{ route('addAffectedBridge') }}"
            >
                Add Bridge
            </flux:button>
        </div>

    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session()->has('message'))
        <div class="mb-4 px-4 py-3 rounded border border-green-400 bg-green-500 text-white font-semibold shadow">
            ✔ {{ session('message') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">

            <thead class="bg-gray-50 dark:bg-zinc-600">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium">No.</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Bridge Name</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Age</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Length</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Latitude</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Longitude</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Map</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($this->collections() as $index => $item)
                    <tr class="border-b">

                        <td class="px-4 py-2">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-4 py-2">
                            {{ $item['bridgename'] ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-2">
                            {{ $item['bridgeAge'] ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-2">
                            {{ $item['bridgeLength'] ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-2">
                            {{ $item['latitude'] ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-2">
                            {{ $item['longtitude'] ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-2">
                            @if(!empty($item['latitude']) && !empty($item['longtitude']))
                                <iframe
                                    width="180"
                                    height="120"
                                    style="border:0"
                                    loading="lazy"
                                    src="https://www.google.com/maps?q={{ $item['latitude'] }},{{ $item['longtitude'] }}&z=15&output=embed">
                                </iframe>
                            @endif
                        </td>

                        <td class="px-4 py-2">
                            <div class="flex gap-2">

                                <flux:button
                                    variant="primary"
                                    color="blue"
                                    wire:navigate
                                    href="{{ route('editAffectedBridge', $item['_id']) }}">
                                    Edit
                                </flux:button>

                                <flux:button
                                    variant="danger"
                                    wire:click="deleteBridge('{{ $item['_id'] }}')">
                                    Delete
                                </flux:button>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            No bridges found
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

        <div class="mt-4">
            {{ $this->collections->links() }}
        </div>
    </div>

</div>