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

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->firebaseCollections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <option value="<?php echo e($collection); ?>">
                    <?php echo e($collection); ?>

                </option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
        <div class="text-green-600 font-medium">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div><?php /**PATH C:\Users\PNP-ITMS\Documents\laravel\adminNATALGED\resources\views/livewire/admin/add-admin.blade.php ENDPATH**/ ?>