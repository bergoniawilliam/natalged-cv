<div class="p-6 max-w-xl space-y-3">

    <h1 class="text-xl font-bold">Edit Admin</h1>

    <input wire:model="first_name" placeholder="First Name" class="border p-2 w-full">
    <input wire:model="middle_name" placeholder="Middle Name" class="border p-2 w-full">
    <input wire:model="last_name" placeholder="Last Name" class="border p-2 w-full">

    <input wire:model="email" placeholder="Email" class="border p-2 w-full">
    <input wire:model="password" type="password" placeholder="Password (optional)" class="border p-2 w-full">

    <input wire:model="rank" placeholder="Rank" class="border p-2 w-full">

    {{-- ROLE --}}
    <div>
        <label class="font-bold">Role:</label>

        <select wire:model="role" class="border p-2 w-full rounded">
            <option value="">Select Role</option>

            @foreach($roles as $role)
                <option value="{{ $role }}">
                    {{ $role }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- COLLECTION --}}
    <div>
        <label class="font-bold mr-2">Collection:</label>

        <select wire:model="selectedCollection" class="border px-2 py-1 rounded">

            <option value="">Select Collection</option>

            @foreach($this->firebaseCollections() as $collection)
                <option value="{{ $collection }}">
                    {{ $collection }}
                </option>
            @endforeach

        </select>
    </div>

    {{-- BUTTON --}}
    <button wire:click="update"
        class="bg-green-600 text-white px-4 py-2 rounded">
        Update Admin 
    </button>

    {{-- SUCCESS --}}
    @if (session()->has('success'))
        <div class="text-green-600">
            {{ session('success') }}
        </div>
    @endif

</div>