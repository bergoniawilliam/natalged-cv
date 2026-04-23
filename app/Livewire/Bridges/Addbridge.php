<?php

namespace App\Livewire\Bridges;

use Livewire\Component;
use Kreait\Firebase\Factory;

class Addbridge extends Component
{
    public $ReferenceName, $BridgeID, $WaterLevel;

    public function save()
    {
        $this->validate([
            'ReferenceName' => 'required',
            'BridgeID' => 'required',
            'WaterLevel' => 'required',
            
        ]);

        $factory = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

        // ✅ FIXED LINE
        $db = $factory->createFirestore()->database();

        try {
            $db->collection('ReferenceBridge')->add([
                'ReferenceName' => $this->ReferenceName,
                'BridgeID' => $this->BridgeID,
                'WaterLevel' => $this->WaterLevel,
            ]);

            $this->reset();

            session()->flash('message', 'Bridge added successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.bridges.addbridge');
    }
}