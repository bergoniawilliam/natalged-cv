<?php

namespace App\Livewire\Bridges;

use Livewire\Component;
use Kreait\Firebase\Factory;

class Editbridge extends Component
{
    public $id;
    public $ReferenceName, $BridgeID, $WaterLevel;
 
    // LOAD DATA
    public function mount($id)
    {
        $this->id = $id;

        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

        $firestore = $factory->createFirestore()->database();

        $doc = $firestore->collection('ReferenceBridge')->document($id)->snapshot();

        if ($doc->exists()) {
            $data = $doc->data();

            $this->ReferenceName = $data['ReferenceName'] ?? '';
            $this->BridgeID = $data['BridgeID'] ?? '';
            $this->WaterLevel = $data['WaterLevel'] ?? '';
        }
    }

    // UPDATE FUNCTION
    public function update()
    {
        $this->validate([
            'ReferenceName' => 'required',
            'BridgeID' => 'required',
            'WaterLevel' => 'required',
        ]);

        try {
            $factory = (new Factory)
                ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

            $firestore = $factory->createFirestore()->database();

            $firestore->collection('ReferenceBridge')
                ->document($this->id)
                ->set([
                    'ReferenceName' => $this->ReferenceName,
                    'BridgeID' => $this->BridgeID,
                    'WaterLevel' => $this->WaterLevel,
                ]);

            session()->flash('message', 'Bridge updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.bridges.editbridge');
    }
}