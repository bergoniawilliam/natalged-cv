<?php

namespace App\Livewire\Evacuation;

use Livewire\Component;
use Kreait\Firebase\Factory;

class EditEvacuation extends Component
{
    public $id;
    public $Evac_name,$Address,$capacity, $latitude, $longtitude;
    public function mount($id)
    {
        $this->id = $id;

        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

        $firestore = $factory->createFirestore()->database();

        $doc = $firestore->collection('AffectedEvacuationCenter')->document($id)->snapshot();

        if ($doc->exists()) {
            $data = $doc->data();

            $this->Evac_name = $data['Evac_name'] ?? '';
            $this->Address = $data['Address'] ?? '';
            $this->capacity = $data['capacity'] ?? '';
            $this->latitude = $data['latitude'] ?? '';
            $this->longtitude = $data['longtitude'] ?? '';
            
            
        }
    }
    public function update()
    {
        $this->validate([
            'Evac_name' => 'required',
            'Address' => 'required',
            'capacity' => 'required',
            'latitude' => 'required',
            'longtitude' => 'required'
           
        ]);

        try {
            $factory = (new Factory)
                ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

            $firestore = $factory->createFirestore()->database();

            // ✅ CHECK DUPLICATE (exclude current document)
            $documents = $firestore->collection('AffectedEvacuationCenter')->documents();

            foreach ($documents as $doc) {
                if (!$doc->exists()) continue;

                // skip current editing record
                if ($doc->id() == $this->id) continue;

                $data = $doc->data();

                if (
                    strtolower(trim($data['Evac_name'] ?? '')) === strtolower(trim($this->Evac_name)) &&
                    strtolower(trim($data['Address'] ?? '')) === strtolower(trim($this->Address)) &&
                    strtolower(trim($data['capacity'] ?? '')) === strtolower(trim($this->capacity)) &&
                    strtolower(trim($data['latitude'] ?? '')) === strtolower(trim($this->latitude)) &&
                    strtolower(trim($data['longtitude'] ?? '')) === strtolower(trim($this->longtitude))

                    
                ) { 
                    session()->flash(
                        'error',
                        'Duplicate Evacuation Center with same location already exists.'
                    );
                    return;
                }
            }

            // ✅ UPDATE RECORD
            $firestore->collection('AffectedEvacuationCenter')
                ->document($this->id)
                ->set([
                    'Evac_name' => trim($this->Evac_name),
                    'Address' => trim($this->Address),
                    'capacity' => trim($this->capacity),
                    'latitude' => trim($this->latitude),
                    'longtitude' => trim($this->longtitude),
                  
                ], ['merge' => true]);

            session()->flash('message', 'Evacuation Center updated successfully!');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.evacuation.edit-evacuation');
    }
}
 