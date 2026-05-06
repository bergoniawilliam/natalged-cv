
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3 justify-center items-center">

                @foreach($firebase_collections as $col)
                    @if($col !== 'Administrators')

                        <div class="flex flex-col items-center justify-center text-center rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">

                            <h2 class="text-sm text-gray-500">
                                {{ $col }}
                            </h2>

                            <p class="text-3xl font-bold mt-2">
                                {{ $counts[$col] ?? 0 }}
                            </p>

                        </div>

                    @endif
                @endforeach

            </div>
          
       
                @if(isset($counts['Administrators']))
                    <div class="flex flex-col items-center justify-center text-center rounded-xl border border-blue-500 p-6 bg-blue-50 dark:bg-blue-900/20">

                        <h2 class="text-xl text-blue-600 font-semibold">
                            Administrators
                        </h2>

                        <p class="text-5xl font-bold text-blue-700 mt-2">
                            {{ $counts['Administrators'] }}
                        </p>

                    </div>
                @endif
          
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>

