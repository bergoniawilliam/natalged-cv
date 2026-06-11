<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-sm">
        <div class="p-6 space-y-6">

            {{-- HEADER --}}
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-base font-bold text-neutral-800 dark:text-neutral-100 tracking-tight">
                        User Access Control
                    </h2>
                    <p class="text-xs text-neutral-400 mt-0.5">
                        Manage roles and permissions across the system
                    </p>
                </div>

                <div class="text-xs bg-neutral-100 dark:bg-neutral-800 text-neutral-500 dark:text-neutral-400 px-3 py-1 rounded-full border border-neutral-200 dark:border-neutral-700">
                    {{ count($roles) }} roles
                </div>
            </div>

            {{-- ROLE SELECT CARD --}}
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 p-4 space-y-2">

                <label class="text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                    Select Role
                </label>

                <select
                    wire:model.live="selectedRole"
                    class="w-full rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-800 dark:text-neutral-100 focus:ring-2 focus:ring-blue-500 focus:outline-none"
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

            {{-- PERMISSIONS SECTION --}}
            <div class="space-y-3">

                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">
                        Permissions
                    </h3>

                    <span class="text-xs text-neutral-400">
                        {{ count($permissions) }} items
                    </span>
                </div>

                {{-- FIXED GRID --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-[420px] overflow-y-auto pr-1">

                    @foreach($permissions as $permission)
                        <label
                            wire:key="perm-{{ $permission->id }}"
                            class="flex items-center gap-2 p-2 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition text-sm"
                        >
                            <input
                                type="checkbox"
                                wire:model="rolePermissions"
                                value="{{ (string) $permission->name }}"
                                class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500"
                            >

                            <span class="truncate text-neutral-700 dark:text-neutral-200">
                                {{ $permission->name }}
                            </span>
                        </label>
                    @endforeach

                </div>

            </div>

            {{-- ACTION BAR --}}
            <div class="flex items-center justify-between pt-4 border-t border-neutral-200 dark:border-neutral-700">

                {{-- SUCCESS MESSAGE --}}
                @if (session()->has('success'))
                    <div class="text-xs text-green-600 dark:text-green-400 font-medium">
                        {{ session('success') }}
                    </div>
                @else
                    <div></div>
                @endif

                <button
                    wire:click="save"
                    class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-sm transition"
                >
                    Save Permissions
                </button>

            </div>

        </div>
    </div>

</div>