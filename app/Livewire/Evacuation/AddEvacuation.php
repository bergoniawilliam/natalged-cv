<?php

namespace App\Livewire\Evacuation;

use Livewire\Component;
use Kreait\Firebase\Factory;

class AddEvacuation extends Component
{
      public $Evac_name, $latitude, $longtitude;
    public function render()
    {
        return view('livewire.evacuation.add-evacuation');
    }
    public function save() 
    { 
        $this->validate([
            'Evac_name' => 'required', 
            'latitude' => 'required', 
            'longtitude' => 'required', 
            
        ]);

        $factory = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

        $db = $factory->createFirestore()->database();

        try {

            // ✅ CHECK DUPLICATE FIRST
            $documents = $db->collection('AffectedEvacuationCenter')->documents();

            foreach ($documents as $doc) {
                if (!$doc->exists()) continue;

                $data = $doc->data();

                if (
                    strtolower(trim($data['Evac_name'] ?? '')) === strtolower(trim($this->Evac_name)) &&
                    strtolower(trim($data['latitude'] ?? '')) === strtolower(trim($this->latitude)) &&
                    strtolower(trim($data['longtitude'] ?? '')) === strtolower(trim($this->longtitude))

                   
                ) {
                    session()->flash('error', 'Duplicate Evacuation with same location already exists.');
                    return;
                }
            }

            // ✅ SAVE IF NO DUPLICATE
            $db->collection('AffectedEvacuationCenter')->add([
                'Evac_name' => trim($this->Evac_name),
                'latitude' => trim($this->latitude),
                'longtitude' => trim($this->longtitude),
                
            ]);

            $this->reset(['Evac_name','latitude','longtitude']);

            session()->flash('message', 'Evacuation added successfully.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }
}
 