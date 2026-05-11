<div>

    <h1 class="text-2xl font-bold mb-4">Affected Barangays</h1>

    {{-- FILTERS --}}
    <div class="flex flex-wrap gap-4 mb-4 items-center justify-between">

        <div class="flex gap-3 items-center">
            <input type="text"
                wire:model.live.debounce.500ms="query"
                placeholder="Search barangay name..."
                class="border px-3 py-1 rounded">
        </div>

        <div>
            <label class="font-bold mr-2">Per Page:</label>
            <select wire:model.live="perPage" class="border px-2 py-1 rounded">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        {{-- ADD BUTTON --}}
        <div>
            <flux:button
                wire:navigate
                href="{{ route('addBarangayAffected') }}"
            >
                Add Barangay
            </flux:button>
        </div>

    </div>

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

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">

            <thead class="bg-gray-50 dark:bg-zinc-600">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium">No.</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Barangay Name</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Families Affected</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Latitude</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Longitude</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($this->collections() as $index => $item)
                    <tr class="border-b">

                        <td class="px-4 py-2 text-sm">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-4 py-2 text-sm">
                            {{ $item['barangayName'] ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-2 text-sm">
                            {{ $item['familiesAffected'] ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-2 text-sm">
                            {{ $item['latitude'] ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-2 text-sm">
                            {{ $item['longtitude'] ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-2 text-sm">
                            <div class="flex gap-2 items-center">

                                {{-- EDIT --}}
                                <flux:button
                                    variant="primary"
                                    color="blue"
                                    wire:navigate
                                    href="{{ route('editBarangayAffected', $item['_id']) }}"
                                >
                                    Edit
                                </flux:button>

                                {{-- DELETE TRIGGER --}}
                                <flux:modal.trigger name="delete-barangay-{{$item['_id']}}">
                                    <flux:button variant="danger">
                                        Delete
                                    </flux:button>
                                </flux:modal.trigger>

                                {{-- DELETE MODAL --}}
                                <flux:modal
                                    name="delete-barangay-{{$item['_id']}}"
                                    wire:key="delete-barangay-{{$item['_id']}}"
                                    class="min-w-[22rem]"
                                >
                                    <div class="space-y-6">

                                        <div>
                                            <flux:heading size="lg">
                                                Delete Barangay?
                                            </flux:heading>

                                            <flux:text class="mt-2">
                                                <div>
                                                    Are you sure you want to delete this barangay?
                                                </div>

                                                <div class="mt-2 text-gray-600">
                                                    Name:
                                                    {{ $item['barangayName'] ?? 'N/A' }}
                                                </div>
                                            </flux:text>
                                        </div>

                                        <div class="flex gap-2">
                                            <flux:spacer />

                                            <flux:modal.close>
                                                <flux:button variant="ghost">
                                                    Cancel
                                                </flux:button>
                                            </flux:modal.close>

                                            <flux:button
                                                wire:click="deleteBarangay('{{ $item['_id'] }}')"
                                                variant="danger"
                                            >
                                                Yes, Delete
                                            </flux:button>
                                        </div>

                                    </div>
                                </flux:modal>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            No barangays found
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $this->collections->links() }}
        </div>
    </div>

</div>