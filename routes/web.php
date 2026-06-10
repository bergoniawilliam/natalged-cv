<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;

use App\Livewire\Uac\RolePermissionPanel;

use App\Livewire\Admin\Admin;
use App\Livewire\Admin\AddAdmin;
use App\Livewire\Admin\EditAdmin;

use App\Livewire\Users\Users;
use App\Livewire\Users\Adduser;
use App\Livewire\Users\Edituser;

use App\Livewire\Bridges\Bridges;
use App\Livewire\Bridges\AddBridge;
use App\Livewire\Bridges\EditBridge;

use App\Livewire\BridgeWaterlevel\RefBridgeWaterlevel;
use App\Livewire\BridgeWaterlevel\AddRefBridgeWaterlevel;
use App\Livewire\BridgeWaterlevel\EditRefBridgeWaterlevel;

use App\Livewire\Road\Roads;
use App\Livewire\Road\AddRoads;
use App\Livewire\Road\EditRoads;

use App\Livewire\Evacuation\Evacuation;
use App\Livewire\Evacuation\AddEvacuation;
use App\Livewire\Evacuation\EditEvacuation;

use App\Livewire\Relation\Relation;
use App\Livewire\Relation\AddRelation;
use App\Livewire\Relation\EditRelation;

use App\Livewire\BridgeAffected\BridgesAffected;
use App\Livewire\BridgeAffected\AddBridgesAffected;
use App\Livewire\BridgeAffected\EditBridgesAffected;

use App\Livewire\BarangayAffected\BarangayAffected;
use App\Livewire\BarangayAffected\AddBarangayAffected;
use App\Livewire\BarangayAffected\EditBarangayAffected;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    // UAC PANEL
    Route::middleware(['permission:uac.view'])
    ->get('/uac/roles', RolePermissionPanel::class)
    ->name('uac.roles');

    // ================= DASHBOARD =================
    Route::middleware(['permission:dashboard.view'])
    ->get('/dashboard', Dashboard::class)
    ->name('dashboard');

    // ================= ADMIN USERS =================
    Route::middleware(['permission:admin.view'])->group(function () {

        Route::get('/admin/users', Admin::class)->name('admin.users');

        Route::middleware(['permission:admin.create'])
            ->get('/admin/add', AddAdmin::class)
            ->name('admin.add');

        Route::middleware(['permission:admin.update'])
            ->get('/admin/users/edit/{id}', EditAdmin::class)
            ->name('admin.edit');
    });

    // ================= USERS MODULE =================
    Route::middleware(['permission:users.view'])->group(function () {

        Route::get('/users', Users::class)->name('users');

        Route::middleware(['permission:users.create'])
            ->get('/users/add', AddUser::class)
            ->name('adduser');

        Route::middleware(['permission:users.update'])
            ->get('/users/edit/{uid}/{collection}', Edituser::class)
            ->name('edituser');
    });

    // ================= BRIDGES =================
    Route::middleware(['permission:bridges.view'])->group(function () {

        Route::get('/bridges', Bridges::class)->name('bridges');

        Route::middleware(['permission:bridges.create'])
            ->get('/bridges/add', AddBridge::class)
            ->name('addbridge');

        Route::middleware(['permission:bridges.update'])
            ->get('/bridges/edit/{id}', EditBridge::class)
            ->name('editbridge');
    });

    // ================= WATERLEVEL =================
    Route::middleware(['permission:waterlevel.view'])->group(function () {

        Route::get('/ref-bridge-waterlevel', RefBridgeWaterlevel::class)
            ->name('RefBridgeWaterlevel');

        Route::middleware(['permission:waterlevel.create'])
            ->get('/ref-bridge-waterlevel/add', AddRefBridgeWaterlevel::class)
            ->name('AddRefBridgeWaterlevel');

        Route::middleware(['permission:waterlevel.update'])
            ->get('/ref-bridge-waterlevel/edit/{id}', EditRefBridgeWaterlevel::class)
            ->name('EditRefBridgeWaterlevel');
    });

    // ================= ROADS =================
    Route::middleware(['permission:roads.view'])->group(function () {

        Route::get('/roads', Roads::class)->name('Roads');

        Route::middleware(['permission:roads.create'])
            ->get('/roads/add', AddRoads::class)
            ->name('addRoad');

        Route::middleware(['permission:roads.update'])
            ->get('/roads/edit/{id}', EditRoads::class)
            ->name('editRoad');
    });

    // ================= EVACUATION =================
    Route::middleware(['permission:evacuation.view'])->group(function () {

        Route::get('/evacuation', Evacuation::class)->name('Evacuation');

        Route::middleware(['permission:evacuation.create'])
            ->get('/evacuation/add', AddEvacuation::class)
            ->name('addEvacuation');

        Route::middleware(['permission:evacuation.update'])
            ->get('/evacuation/edit/{id}', EditEvacuation::class)
            ->name('editEvacuation');
    });

    // ================= RELATION =================
    Route::middleware(['permission:relation.view'])->group(function () {

        Route::get('/relation', Relation::class)->name('Relation');

        Route::middleware(['permission:relation.create'])
            ->get('/relation/add', AddRelation::class)
            ->name('addRelation');

        Route::middleware(['permission:relation.update'])
            ->get('/relation/edit/{id}', EditRelation::class)
            ->name('editRelation');
    });

    // ================= AFFECTED BRIDGE =================
    Route::middleware(['permission:affected-bridge.view'])->group(function () {

        Route::get('/affected-bridge', BridgesAffected::class)
            ->name('affected-bridge');

        Route::middleware(['permission:affected-bridge.create'])
            ->get('/affected-bridge/add', AddBridgesAffected::class)
            ->name('addAffectedBridge');

        Route::middleware(['permission:affected-bridge.update'])
            ->get('/affected-bridge/edit/{id}', EditBridgesAffected::class)
            ->name('editAffectedBridge');
    });

    // ================= BARANGAY =================
    Route::middleware(['permission:barangay-affected.view'])->group(function () {

        Route::get('/barangay-affected', BarangayAffected::class)
            ->name('barangay-affected');

        Route::middleware(['permission:barangay-affected.create'])
            ->get('/barangay-affected/add', AddBarangayAffected::class)
            ->name('addBarangayAffected');

        Route::middleware(['permission:barangay-affected.update'])
            ->get('/barangay-affected/edit/{id}', EditBarangayAffected::class)
            ->name('editBarangayAffected');
    });

     

    

});

require __DIR__.'/settings.php';
