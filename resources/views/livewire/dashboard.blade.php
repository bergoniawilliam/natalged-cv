<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-sm">
        <div class="p-6">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-base font-bold text-neutral-800 dark:text-neutral-100 tracking-tight">
                        Personnel Breakdown
                    </h2>
                    <p class="text-xs text-neutral-400 mt-0.5">
                        Total: <span class="font-semibold text-neutral-600 dark:text-neutral-300 tabular-nums">{{ collect($counts)->sum() }}</span> personnel across all units
                    </p>
                </div>
                <span class="text-xs bg-neutral-100 dark:bg-neutral-800 text-neutral-500 dark:text-neutral-400 px-3 py-1 rounded-full border border-neutral-200 dark:border-neutral-700">
                    {{ count($firebase_collections) }} units
                </span>
            </div>

            {{-- Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($firebase_collections as $col)
                    @php
                        $total = collect($counts)->sum();
                        $val   = $counts[$col] ?? 0;
                        $pct   = $total > 0 ? round($val / $total * 100, 1) : 0;
                        $isAdmin = $col === 'Administrators';
                    @endphp
                    <div class="flex flex-col rounded-xl border px-4 py-4
                        {{ $isAdmin
                            ? 'border-blue-300 dark:border-blue-700 bg-blue-50 dark:bg-blue-950/30'
                            : 'border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800' }}">

                        <span class="text-xs font-semibold uppercase tracking-wider
                            {{ $isAdmin ? 'text-blue-500 dark:text-blue-400' : 'text-neutral-400 dark:text-neutral-500' }}">
                            {{ $col }}
                        </span>

                        <span class="text-3xl font-bold tabular-nums mt-2
                            {{ $isAdmin ? 'text-blue-700 dark:text-blue-300' : 'text-neutral-800 dark:text-neutral-100' }}">
                            {{ $val }}
                        </span>

                        {{-- Progress bar --}}
                        <div class="mt-3 h-1 rounded-full bg-neutral-200 dark:bg-neutral-700 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500
                                {{ $isAdmin ? 'bg-blue-500' : 'bg-neutral-400 dark:bg-neutral-500' }}"
                                 style="width: {{ $pct }}%">
                            </div>
                        </div>

                        <span class="text-xs mt-1.5
                            {{ $isAdmin ? 'text-blue-400' : 'text-neutral-400' }}">
                            {{ $pct }}% of total
                        </span>

                    </div>
                @endforeach
            </div>

        </div>
    </div>

</div>