<?php

namespace App\Livewire\BridgeWaterlevel;

use Livewire\Component;
use Kreait\Firebase\Factory;

class EditRefBridgeWaterlevel extends Component
{
    public $id;
    public $Bridge_name, $Water_lvl;

    public function mount($id)
    {
        $this->id = $id;

        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

        $firestore = $factory->createFirestore()->database();

        $doc = $firestore->collection('RefBridgeWaterlevel')->document($id)->snapshot();

        if ($doc->exists()) {
            $data = $doc->data();

            $this->Bridge_name = $data['Bridge_name'] ?? '';
            $this->Water_lvl = $data['Water_lvl'] ?? '';
            
        }
    }
    public function update()
    {
        $this->validate([
            'Bridge_name' => 'required',
            'Water_lvl'   => 'required',
        ]);

        try {
            $factory = (new Factory)
                ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

            $firestore = $factory->createFirestore()->database();

            // ✅ CHECK DUPLICATE (exclude current document)
            $documents = $firestore->collection('RefBridgeWaterlevel')->documents();

            foreach ($documents as $doc) {
                if (!$doc->exists()) continue;

                // skip current editing record
                if ($doc->id() == $this->id) continue;

                $data = $doc->data();

                if (
                    strtolower(trim($data['Bridge_name'] ?? '')) === strtolower(trim($this->Bridge_name)) &&
                    strtolower(trim($data['Water_lvl'] ?? '')) === strtolower(trim($this->Water_lvl))
                ) {
                    session()->flash(
                        'error',
                        'Duplicate bridge name with same water level already exists.'
                    );
                    return;
                }
            }

            // ✅ UPDATE RECORD
            $firestore->collection('RefBridgeWaterlevel')
                ->document($this->id)
                ->set([
                    'Bridge_name' => trim($this->Bridge_name),
                    'Water_lvl'   => trim($this->Water_lvl),
                ], ['merge' => true]);

            session()->flash('message', 'Bridge updated successfully!');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.bridge-waterlevel.edit-ref-bridge-waterlevel');
    }
}
