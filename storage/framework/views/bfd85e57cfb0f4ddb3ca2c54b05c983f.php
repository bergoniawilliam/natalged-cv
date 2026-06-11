<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-sm">
        <div class="p-6 space-y-6">

            
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
                    <?php echo e(count($roles)); ?> roles
                </div>
            </div>

            
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 p-4 space-y-2">

                <label class="text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                    Select Role
                </label>

                <select
                    wire:model.live="selectedRole"
                    class="w-full rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-800 dark:text-neutral-100 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'role-select'; ?>wire:key="role-select"
                >
                    <option value="">Select Role</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($role->id); ?>">
                            <?php echo e($role->name); ?>

                        </option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>

            </div>

            
            <div class="space-y-3">

                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">
                        Permissions
                    </h3>

                    <span class="text-xs text-neutral-400">
                        <?php echo e(count($permissions)); ?> items
                    </span>
                </div>

                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-[420px] overflow-y-auto pr-1">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <label
                            <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'perm-'.e($permission->id).''; ?>wire:key="perm-<?php echo e($permission->id); ?>"
                            class="flex items-center gap-2 p-2 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition text-sm"
                        >
                            <input
                                type="checkbox"
                                wire:model="rolePermissions"
                                value="<?php echo e((string) $permission->name); ?>"
                                class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500"
                            >

                            <span class="truncate text-neutral-700 dark:text-neutral-200">
                                <?php echo e($permission->name); ?>

                            </span>
                        </label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                </div>

            </div>

            
            <div class="flex items-center justify-between pt-4 border-t border-neutral-200 dark:border-neutral-700">

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
                    <div class="text-xs text-green-600 dark:text-green-400 font-medium">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php else: ?>
                    <div></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <button
                    wire:click="save"
                    class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-sm transition"
                >
                    Save Permissions
                </button>

            </div>

        </div>
    </div>

</div><?php /**PATH C:\Users\PNP-ITMS\Documents\laravel\adminNATALGED\resources\views/livewire/uac/role-permission-panel.blade.php ENDPATH**/ ?>