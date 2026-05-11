<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])
->group(function(){
    // Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');


    Route::livewire('users', 'users')->name('users');
    Route::livewire('adduser', 'users.adduser')->name('adduser');
    Route::livewire('users/edit/{uid}/{collection}', 'users.edituser')->name('edituser');


    Route::livewire('bridges', 'bridges')->name('bridges');
    Route::livewire('addbridge', 'bridges.addbridge')->name('addbridge');
    Route::livewire('bridges/edit/{id}', 'bridges.editbridge')->name('editbridge');

   Route::livewire('/RefBridgeWaterlevel','bridge-waterlevel.ref-bridge-waterlevel')->name('RefBridgeWaterlevel');});
    Route::livewire(
        '/AddRefBridgeWaterlevel',
        'bridge-waterlevel.add-ref-bridge-waterlevel'
    )->name('AddRefBridgeWaterlevel');
    Route::livewire(
        '/EditRefBridgeWaterlevel/{id}',
        'bridge-waterlevel.edit-ref-bridge-waterlevel'
    )->name('EditRefBridgeWaterlevel'); 

    Route::livewire(
        '/Roads', 
        'road.roads'
    )->name('Roads');
    Route::livewire('/addRoad', 'road.add-roads')->name('addRoad');
    Route::livewire('/editRoad/{id}', 'road.edit-roads')->name('editRoad');

    Route::livewire(
        '/Evacuation',
        'evacuation.evacuation'
    )->name('Evacuation');
    Route::livewire('/addEvacuation', 'evacuation.add-evacuation')->name('addEvacuation');
    Route::livewire('/editEvacuation/{id}', 'evacuation.edit-Evacuation')->name('editEvacuation');


    Route::livewire(
        '/Relation',
        'relation.relation'
    )->name('Relation');
    Route::livewire(
        '/addRelation',
        'relation.add-relation'
    )->name('addRelation');
    Route::livewire('/editRelation/{id}', 'relation.edit-relation')->name('editRelation');


    Route::livewire(
        '/affected-bridge',
        'bridge-affected.bridges-affected'
    )->name('affected-bridge');
    Route::livewire(
        '/affected-bridge/edit/{id}',
        'bridge-affected.edit-bridges-affected'
    )->name('editAffectedBridge');
    Route::livewire(
        '/addAffectedBridge',
        'bridge-affected.add-bridges-affected'
    )->name('addAffectedBridge'); 


    Route::livewire(
        '/barangay-affected',
        'barangay-affected.barangay-affected'
    )->name('barangay-affected');
    Route::livewire('/barangay-affected/add', 'barangay-affected.add-barangay-affected')->name('addBarangayAffected');

Route::livewire('/barangay-affected/edit/{id}', 'barangay-affected.edit-barangay-affected')->name('editBarangayAffected');
        


require __DIR__.'/settings.php';
