<div class="p-6 max-w-xl space-y-4">

    <h1 class="text-xl font-bold">Add User</h1>

    <input
        wire:model="first_name"
        placeholder="First Name"
        class="border p-2 w-full rounded"
    >

    <input
        wire:model="middle_name"
        placeholder="Middle Name"
        class="border p-2 w-full rounded"
    >

    <input
        wire:model="last_name"
        placeholder="Last Name"
        class="border p-2 w-full rounded"
    >

    <input
        wire:model="email"
        placeholder="Email"
        class="border p-2 w-full rounded"
    >

    <input
        wire:model="password"
        type="password"
        placeholder="Password"
        class="border p-2 w-full rounded"
    >

    <input
        wire:model="rank"
        placeholder="Rank"
        class="border p-2 w-full rounded"
    >

    <div>
        <label class="block font-semibold mb-1">
            Collection
        </label>

        <select
            wire:model="selectedCollection"
            class="border p-2 w-full rounded"
        >
            <option value="">Select Collection</option>

            @foreach($this->firebaseCollections as $collection)
                <option value="{{ $collection }}">
                    {{ $collection }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block font-semibold mb-1">
            Role
        </label>

        <select
            wire:model="role"
            class="border p-2 w-full rounded"
        >
            <option value="Super Admin">Super Admin</option>
            <option value="Admin">Admin</option>
            <option value="Encoder">Encoder</option>
            <option value="Viewer">Viewer</option>
        </select>
    </div>

    <button
        wire:click="save"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
    >
        Save User
    </button>

    @if (session()->has('success'))
        <div class="text-green-600 font-medium">
            {{ session('success') }}
        </div>
    @endif

</div>