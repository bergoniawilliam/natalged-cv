<?php

namespace App\Livewire\Road;

use Livewire\Component;
use Kreait\Firebase\Factory;

class AddRoads extends Component
{
    public $Road_name, $latitude, $longtitude;
    public function render()
    {
        return view('livewire.road.add-roads');
    }
    public function save()
    { 
        $this->validate([
            'Road_name' => 'required', 
            'latitude' => 'required', 
            'longtitude' => 'required', 
            
        ]);

        $factory = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

        $db = $factory->createFirestore()->database();

        try {

            // ✅ CHECK DUPLICATE FIRST
            $documents = $db->collection('AffectedRoads')->documents();

            foreach ($documents as $doc) {
                if (!$doc->exists()) continue;

                $data = $doc->data();

                if (
                    strtolower(trim($data['Road_name'] ?? '')) === strtolower(trim($this->Road_name)) &&
                    strtolower(trim($data['latitude'] ?? '')) === strtolower(trim($this->latitude)) &&
                    strtolower(trim($data['longtitude'] ?? '')) === strtolower(trim($this->longtitude))

                   
                ) {
                    session()->flash('error', 'Duplicate road with same location already exists.');
                    return;
                }
            }

            // ✅ SAVE IF NO DUPLICATE
            $db->collection('AffectedRoads')->add([
                'Road_name' => trim($this->Road_name),
                'latitude' => trim($this->latitude),
                'longtitude' => trim($this->longtitude),
                
            ]);

            $this->reset(['Road_name','latitude','longtitude']);

            session()->flash('message', 'Road added successfully.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }
}
 