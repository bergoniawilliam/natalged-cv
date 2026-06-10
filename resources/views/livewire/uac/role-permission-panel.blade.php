<div class="p-6 space-y-4">

    <h1 class="text-xl font-bold">UAC Panel</h1>

   

    {{-- ROLE SELECT --}}
    <div>
        <label class="font-bold">Select Role</label>

        <select
            wire:model.live="selectedRole"
            class="border p-2 w-full rounded"
            wire:key="role-select"
        >
            <option value="">Select Role</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}">
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- PERMISSIONS --}}
    <div class="grid grid-cols-3 gap-2 mt-4">
        @foreach($permissions as $permission)
            <label
                wire:key="perm-{{ $permission->id }}"
                class="flex items-center gap-2 p-2 border rounded"
            >
                <input
                    type="checkbox"
                    wire:model="rolePermissions"
                    value="{{ (string) $permission->name }}"
                >

                <span class="text-sm">
                    {{ $permission->name }}
                </span>
            </label>
        @endforeach
    </div>

    {{-- SAVE BUTTON --}}
    <div class="pt-4">
        <button
            wire:click="save"
            class="bg-blue-600 text-white px-4 py-2 rounded"
        >
            Save Permissions
        </button>
    </div>
     {{-- SUCCESS MESSAGE --}}
    @if (session()->has('success'))
        <div class="text-green-600 font-medium">
            {{ session('success') }}
        </div>
    @endif

</div>