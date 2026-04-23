<div>
    <h1 class="text-2xl font-bold mb-4">Users</h1>
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
                href="{{ route('adduser') }}"
            >
                Add User
            </flux:button>
        </div>
    </div>
    <div x-data=""
        x-on:triggerDeleteConfirmation.window="if(confirm('Are you sure you want to delete this user?')) { @this.call('deleteUser') }"
        x-on:showAlert.window="alert($event.detail)">
    </div>
    <!-- Loading indicator -->
    <div wire:loading wire:target="selectedCollection, query" class="mb-4 text-center text-blue-500 font-bold">
        Loading users...
    </div>
    @if (session()->has('message'))
        <div class="mt-3 text-green-500 font-bold">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-3 text-red-500 font-bold">
            {{ session('error') }}
        </div>
    @endif
    

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
            <thead class="bg-gray-50 dark:bg-zinc-600">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">No.</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">Email</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">Rank</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">Name</th>
                    <!-- <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">ID</th> -->
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">SubUnit</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">Unit</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">Call Sign</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">Role</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">Contact No</th>
                     <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">Payslip No</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">Station</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">Created At</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->collections() as $index =>$collection)
                    <tr>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['email'] ?? 'NA' }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['Rank'] ?? 'NA' }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['Name'] ?? 'NA' }}</td>
                        <!-- <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['_id'] ?? 'NA' }}</td> -->
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['SubUnit'] ?? 'NA' }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['Unit'] ?? 'NA' }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['CallSign'] ?? 'NA' }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['Role'] ?? 'NA' }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['ContactNo'] ?? 'NA' }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['Payslip'] ?? 'NA' }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['Station'] ?? 'NA' }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $collection['CreatedAt'] ? \Carbon\Carbon::parse($collection['CreatedAt'])->format('M d, Y') : 'NA' }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">
                            <div class="flex gap-2 items-center">
                                <!-- Edit Button -->
                                <flux:button variant="primary" color="blue" wire:navigate  href="{{ route('edituser', ['uid' => $collection['_id'], 'collection' => $selectedCollection]) }}">
                                Edit
                                </flux:button>
                                <flux:button 
                                    variant="danger" 
                                    color="red"
                                    x-data 
                                    x-on:click="if(confirm('Are you sure you want to delete this user?')) { $wire.deleteUser('{{ $collection['_id'] }}') }">
                                    Delete
                                </flux:button>
                                
                                
                                <flux:modal.trigger 
                                    name="change-password-{{$collection['_id']}}"
                                    wire:click="openChangePasswordModal">
                                    <flux:button variant="primary">
                                        Change Password
                                    </flux:button>
                                </flux:modal.trigger>
                                <flux:modal name="change-password-{{$collection['_id']}}" 
                                    wire:key="change-password-{{$collection['_id']}}" 
                                    class="min-w-[24rem]">

                                <div 
                                x-data
                                x-on:close-password-modal.window="setTimeout(()=>{ $flux.modal.close() },2000)"
                                >

                                    <div class="space-y-6">

                                        <div>
                                            <flux:heading size="lg">Change Password</flux:heading>

                                            <flux:text class="mt-2">
                                                ID: {{ $collection['_id'] }} <br>
                                                Name: {{ $collection['Name'] ?? 'No Name' }}
                                            </flux:text>
                                        </div>

                                        {{-- REALTIME NOTIFICATION --}}
                                        @if($message)

                                            <div class="px-3 py-2 rounded
                                                {{ $messageType == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">

                                                {{ $message }}

                                            </div>

                                        @endif


                                        <div class="space-y-4">

                                            <!-- NEW PASSWORD -->
                                            <div x-data="{ show:false }" class="relative">

                                                <input
                                                    x-bind:type="show ? 'text' : 'password'"
                                                    wire:model="newPassword"
                                                    placeholder="New Password"
                                                    class="w-full border rounded-lg px-3 py-2 pr-10"
                                                >

                                                <button
                                                    type="button"
                                                    @click="show=!show"
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">

                                                    <span x-show="!show">👁</span>
                                                    <span x-show="show">🙈</span>

                                                </button>

                                            </div>

                                            <!-- CONFIRM PASSWORD -->
                                            <div x-data="{ show:false }" class="relative">

                                                <input
                                                    x-bind:type="show ? 'text' : 'password'"
                                                    wire:model="confirmPassword"
                                                    placeholder="Confirm Password"
                                                    class="w-full border rounded-lg px-3 py-2 pr-10"
                                                >

                                                <button
                                                    type="button"
                                                    @click="show=!show"
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">

                                                    <span x-show="!show">👁</span>
                                                    <span x-show="show">🙈</span>

                                                </button>

                                            </div>

                                            @error('newPassword')
                                                <div class="text-red-500 text-sm">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>

                                        <div class="flex gap-2">

                                            <flux:spacer />

                                            <flux:modal.close>
                                                <flux:button variant="ghost">
                                                    Cancel
                                                </flux:button>
                                            </flux:modal.close>

                                            <flux:button 
                                                wire:click="changePassword('{{ $collection['_id'] }}')" 
                                                variant="primary">

                                                Update Password

                                            </flux:button>

                                        </div>

                                    </div>

                                </div>

                                </flux:modal>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        
        {{ $this->collections->links() }}
    </div>
 </div>
 

