<div class="p-6 space-y-4">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">

        <h1 class="text-xl font-bold">Users</h1>
         <a href="{{ route('admin.add') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">

            + Add User
        </a>
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search users..."
            class="border px-3 py-2 rounded-lg w-64 text-sm"
        />

    </div>

    {{-- TABLE --}}
    <div class="bg-white shadow rounded-xl overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-left text-xs uppercase">
                <tr>
                    <th class="px-4 py-3">Rank</th>
                    <th class="px-4 py-3">First Name</th>
                    <th class="px-4 py-3">Middle Name</th>
                    <th class="px-4 py-3">Last Name</th>
                    <th class="px-4 py-3">Qualifier</th>
                    <th class="px-4 py-3">Email</th>
                    
                    <th class="px-4 py-3">Collection</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($users as $user)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">
                            {{ $user->rank ?? '-' }}
                        </td>

                        <td class="px-4 py-3 font-medium">
                            {{ $user->first_name }} 
                        </td>
                        <td class="px-4 py-3 font-medium">
                            {{ $user->middle_name }} 
                        </td>
                        <td class="px-4 py-3 font-medium">
                            {{ $user->last_name }} 
                        </td>
                        <td class="px-4 py-3 font-medium">
                            {{ $user->qualifier }} 
                        </td>
                        

                        <td class="px-4 py-3">
                            {{ $user->email }}
                        </td>

                        

                        <td class="px-4 py-3">
                            {{ $user->collection ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->created_at->format('F d, Y') }}
                        </td>

                        <td class="px-4 py-3 text-right space-x-2">

                         <button wire:click="edit({{ $user->id }})"
                            class="px-3 py-1 text-xs bg-blue-500 text-white rounded">
                            Edit
                        </button>

                            <button class="px-3 py-1 text-xs bg-red-500 text-white rounded">
                                Delete
                            </button>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            No users found
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    {{-- PAGINATION --}}
    <div>
        {{ $users->links() }}
    </div>

</div>