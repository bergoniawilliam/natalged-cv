<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])
->group(function(){
    Route::view('dashboard', 'dashboard')->name('dashboard');
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


    Route::livewire(
        '/Relation',
        'relation.relation'
    )->name('Relation');
    Route::livewire(
        '/addRelation',
        'relation.add-relation'
    )->name('addRelation');
    


require __DIR__.'/settings.php';
