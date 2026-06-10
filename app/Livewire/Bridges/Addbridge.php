<?php

namespace App\Livewire\Bridges;


use Livewire\Component;
use Kreait\Firebase\Factory;

class AddBridge extends Component
{
    public $ReferenceName, $BridgeID, $WaterLevel;

    public function save()
    {
        $this->validate([
            'ReferenceName' => 'required',
            'BridgeID' => 'required',
            'WaterLevel' => 'required',
            
        ]);

        $credentials = json_decode(env('FIREBASE_CREDENTIALS'), true);

        $credentials['private_key'] = str_replace(
            "\\n",
            "\n",
            $credentials['private_key']
        );

        $factory = (new \Kreait\Firebase\Factory)
            ->withServiceAccount($credentials);

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