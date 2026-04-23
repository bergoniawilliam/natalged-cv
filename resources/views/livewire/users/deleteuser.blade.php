<div>
    @if($message)
        <div class="px-3 py-2 rounded bg-red-100 text-red-700">
            {{ $message }}
        </div>
    @endif

    <button wire:click="deleteUser" class="bg-red-500 text-white px-4 py-2 rounded">
        Confirm Delete
    </button>
</div>