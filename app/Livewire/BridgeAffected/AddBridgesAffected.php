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

            $docs = $db->collection('AffectedBridge')->documents();

            $newName = strtolower(trim($this->bridgename));

            foreach ($docs as $doc) {
                if (!$doc->exists()) continue;

                $data = $doc->data();

                $existingName = strtolower(trim($data['bridgename'] ?? ''));

                // 🔥 DUPLICATE CHECK (NAME ONLY)
                if ($existingName === $newName) {
                    session()->flash('error', 'Bridge name already exists.');
                    return;
                }
            }

            // SAVE
            $db->collection('AffectedBridge')->add([
                'bridgename' => trim($this->bridgename),
                'bridgeAge' => trim($this->bridgeAge),
                'bridgeLength' => trim($this->bridgeLength),
                'latitude' => trim($this->latitude),
                'longtitude' => trim($this->longtitude),
            ]);

            $this->reset();

            session()->flash('message', 'Bridge added successfully!');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}