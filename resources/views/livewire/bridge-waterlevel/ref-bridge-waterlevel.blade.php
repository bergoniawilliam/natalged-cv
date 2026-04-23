<div>
    <h1 class="text-2xl font-bold mb-4">Bridges</h1>
    <div class="flex flex-wrap gap-4 mb-4 items-center justify-between">
        <div class="flex flex-wrap gap-4 mb-4 items-center">
            <div>
                <label for="collection" class="font-bold mr-2">Collection:</label>
                <select wire:model.live.debounce.500ms ="selectedCollection" id="collection" class="border px-2 py-1 rounded">
                    @foreach($firebase_collections as $col)
                        <option value="{{ $col }}">{{ $col }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <input type="text" wire:model.live.debounce.500ms="query" placeholder="Search by Name..." class="border px-2 py-1 rounded">
            </div>
        </div>
        <flux:button wire:click="exportCsv">
            Export CSV
        </flux:button>
        <div>
            <label for="perPage" class="font-bold mr-2">Per Page:</label>
            <select wire:model.live="perPage" id="perPage" class="border px-2 py-1 rounded">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div>
            <flux:button
                wire:navigate
                href="{{ route('AddRefBridgeWaterlevel') }}"
            >
                Add Bridge
            </flux:button>
        </div> 
    </div>
    <div x-data=""
        x-on:triggerDeleteConfirmation.window="if(confirm('Are you sure you want to delete this bridge?')) { @this.call('deleteBridge') }"
        x-on:showAlert.window="alert($event.detail)">
    </div>
    <!-- Loading indicator -->
    <div wire:loading wire:target="selectedCollection, query" class="mb-4 text-center text-blue-500 font-bold">
        Loading Bridges...
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
            <thead class="bg-gray-50 dark:bg-zinc-600">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">No.</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">Bridge_name</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">WaterLevel</th>
    
                  
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->collections() as $index =>$collection)
                    <tr>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['Bridge_name'] ?? 'NA' }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['Water_lvl'] ?? 'NA' }}</td>
                        

                       
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">
                            <div class="flex gap-2 items-center">
                                <!-- Edit Button -->
                                <flux:button 
                                    variant="primary" 
                                    color="blue" 
                                    wire:navigate  
                                    href="{{ route('EditRefBridgeWaterlevel', $collection['_id']) }}"
                                >
                                    Edit
                                </flux:button>

                                <!-- Delete Button -->
                                <!-- <button 
                                    onclick="if(confirm('Are you sure you want to delete this user?')) { @this.call('deleteUser', ' $collection['_id'] }}') }"
                                    class="bg-red-500 text-white px-2 py-1 rounded">
                                    Delete
                                </button> -->
                                <flux:modal.trigger name="delete-bridge-{{$collection['_id']}}">
                                    <flux:button variant="danger">Delete</flux:button>
                                </flux:modal.trigger>
                                <flux:modal name="delete-bridge-{{$collection['_id']}}" wire:key="delete-bridge-{{$collection['_id']}}" class="min-w-[22rem]">
                                    <div class="space-y-6">
                                        <div>
                                            <flux:heading size="lg">Delete Bridge?</flux:heading>
                                            <flux:text class="mt-2">
                                                <div>
                                                    <flux:heading size="lg">Are you sure you want to delete this bridge?</flux:heading>
                                                </div>
                                                @if($collection['_id'])
                                              
                                                Name: {{ $collection['Bridge_name'] ?? "<span class='italic text-gray-400'>No specified Name </span>" }} <br>
                                                Water Level: {{ $collection['Water_lvl'] ?? "<span class='italic text-gray-400'>No specified Name </span>" }} <br>
                                                
                                                @endif
                                                 
                                            </flux:text>
                                        </div>
                                        <div class="flex gap-2">
                                            <flux:spacer />
                                            <flux:modal.close>
                                                <flux:button variant="ghost">Cancel</flux:button>
                                            </flux:modal.close>
                                            <flux:button wire:click="deleteBridge('{{ $collection['_id'] }}')" variant="danger">
                                                Yes
                                            </flux:button>
                                        </div>
                                    </div>
                                </flux:modal>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">No bridges found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        {{ $this->collections->links() }}
    </div>

</div>