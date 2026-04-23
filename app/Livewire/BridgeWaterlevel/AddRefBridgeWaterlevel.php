<?php

namespace App\Livewire\BridgeWaterlevel;

use Livewire\Component;
use Kreait\Firebase\Factory;

class AddRefBridgeWaterlevel extends Component
{
    public $Bridge_name, $Water_lvl;
    public function save()
    { 
        $this->validate([
            'Bridge_name' => 'required', 
            'Water_lvl'   => 'required',
        ]);

        $factory = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

        $db = $factory->createFirestore()->database();

        try {

            // ✅ CHECK DUPLICATE FIRST
            $documents = $db->collection('RefBridgeWaterlevel')->documents();

            foreach ($documents as $doc) {
                if (!$doc->exists()) continue;

                $data = $doc->data();

                if (
                    strtolower(trim($data['Bridge_name'] ?? '')) === strtolower(trim($this->Bridge_name)) &&
                    strtolower(trim($data['Water_lvl'] ?? '')) === strtolower(trim($this->Water_lvl))
                ) {
                    session()->flash('error', 'Duplicate bridge with same water level already exists.');
                    return;
                }
            }

            // ✅ SAVE IF NO DUPLICATE
            $db->collection('RefBridgeWaterlevel')->add([
                'Bridge_name' => trim($this->Bridge_name),
                'Water_lvl'   => trim($this->Water_lvl),
            ]);

            $this->reset(['Bridge_name', 'Water_lvl']);

            session()->flash('message', 'Bridge added successfully.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.bridge-waterlevel.add-ref-bridge-waterlevel');
    }
}
 