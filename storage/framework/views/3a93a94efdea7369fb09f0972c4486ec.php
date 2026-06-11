<div class="p-6 space-y-4">

    
    <div class="flex justify-between items-center">

        <h1 class="text-xl font-bold">Users</h1>
         <a href="<?php echo e(route('admin.add')); ?>"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->hasRole('Admin')): ?>
    <a href="#">Admin Menu</a>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            + Add User
        </a>
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search users..."
            class="border px-3 py-2 rounded-lg w-64 text-sm"
        />

    </div>

    
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

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <?php echo e($user->rank ?? '-'); ?>

                        </td>

                        <td class="px-4 py-3 font-medium">
                            <?php echo e($user->first_name); ?> 
                        </td>
                        <td class="px-4 py-3 font-medium">
                            <?php echo e($user->middle_name); ?> 
                        </td>
                        <td class="px-4 py-3 font-medium">
                            <?php echo e($user->last_name); ?> 
                        </td>
                        <td class="px-4 py-3 font-medium">
                            <?php echo e($user->qualifier); ?> 
                        </td>
                        

                        <td class="px-4 py-3">
                            <?php echo e($user->email); ?>

                        </td>

                        

                        <td class="px-4 py-3">
                            <?php echo e($user->collection ?? '-'); ?>

                        </td>

                        <td class="px-4 py-3">
                            <?php echo e($user->created_at->format('F d, Y')); ?>

                        </td>

                        <td class="px-4 py-3 text-right space-x-2">

                       <a href="<?php echo e(route('admin.edit', $user->id)); ?>"
   class="px-3 py-1 text-xs bg-blue-500 text-white rounded">
   Edit
</a>

                            <button class="px-3 py-1 text-xs bg-red-500 text-white rounded">
                                Delete
                            </button>

                        </td>

                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            No users found
                        </td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </tbody>

        </table>

    </div>

    
    <div>
        <?php echo e($users->links()); ?>

    </div>

</div><?php /**PATH C:\Users\PNP-ITMS\Documents\laravel\adminNATALGED\resources\views/livewire/admin/admin.blade.php ENDPATH**/ ?>