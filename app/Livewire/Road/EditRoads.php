<?php

namespace App\Livewire\Road;

use Livewire\Component;
use Kreait\Firebase\Factory;

class EditRoads extends Component
{
    public $id;
    public $Road_name, $latitude, $longtitude;
    public function mount($id)
    {
        $this->id = $id;

        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

        $firestore = $factory->createFirestore()->database();

        $doc = $firestore->collection('AffectedRoads')->document($id)->snapshot();

        if ($doc->exists()) {
            $data = $doc->data();

            $this->Road_name = $data['Road_name'] ?? '';
            $this->latitude = $data['latitude'] ?? '';
            $this->longtitude = $data['longtitude'] ?? '';
            
            
        }
    }
    public function update()
    {
        $this->validate([
            'Road_name' => 'required',
            'latitude' => 'required',
            'longtitude' => 'required'
           
        ]);

        try {
            $factory = (new Factory)
                ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

            $firestore = $factory->createFirestore()->database();

            // ✅ CHECK DUPLICATE (exclude current document)
            $documents = $firestore->collection('AffectedRoads')->documents();

            foreach ($documents as $doc) {
                if (!$doc->exists()) continue;

                // skip current editing record
                if ($doc->id() == $this->id) continue;

                $data = $doc->data();

                if (
                    strtolower(trim($data['Road_name'] ?? '')) === strtolower(trim($this->Road_name)) &&
                    strtolower(trim($data['latitude'] ?? '')) === strtolower(trim($this->latitude)) &&
                    strtolower(trim($data['longtitude'] ?? '')) === strtolower(trim($this->longtitude))

                    
                ) {
                    session()->flash(
                        'error',
                        'Duplicate road name with same location already exists.'
                    );
                    return;
                }
            }

            // ✅ UPDATE RECORD
            $firestore->collection('AffectedRoads')
                ->document($this->id)
                ->set([
                    'Road_name' => trim($this->Road_name),
                    'latitude' => trim($this->latitude),
                    'longtitude' => trim($this->longtitude),
                  
                ], ['merge' => true]);

            session()->flash('message', 'Road updated successfully!');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.road.edit-roads');
    }
}
 