<?php

namespace App\Livewire\BridgeAffected;

use Livewire\Component;
use Kreait\Firebase\Factory;

class AddBridgesAffected extends Component
{
    public $bridgename, $bridgeAge, $bridgeLength, $latitude, $longtitude;

    public function render()
    {
        return view('livewire.bridge-affected.add-bridges-affected');
    }

    public function save()
    {
        $this->validate([
            'bridgename' => 'required',
            'bridgeAge' => 'required',
            'bridgeLength' => 'required',
            'latitude' => 'required',
            'longtitude' => 'required',
        ]);

        $db = (new Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'))
            ->createFirestore()
            ->database();

        try {

            // CHECK DUPLICATE
            $docs = $db->collection('AffectedBridge')->documents();

            foreach ($docs as $doc) {
                if (!$doc->exists()) continue;

                $data = $doc->data();

                if (
                    strtolower($data['bridgename'] ?? '') === strtolower($this->bridgename) &&
                    $data['latitude'] === $this->latitude &&
                    $data['longtitude'] === $this->longtitude
                ) {
                    session()->flash('error', 'Duplicate bridge already exists.');
                    return;
                }
            }

            // SAVE
            $db->collection('AffectedBridge')->add([
                'bridgename' => $this->bridgename,
                'bridgeAge' => $this->bridgeAge,
                'bridgeLength' => $this->bridgeLength,
                'latitude' => $this->latitude,
                'longtitude' => $this->longtitude,
            ]);

            $this->reset();

            session()->flash('message', 'Bridge added successfully!');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}