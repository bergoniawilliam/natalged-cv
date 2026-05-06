<?php

namespace App\Livewire\BridgeAffected;

use Livewire\Component;
use Google\Cloud\Firestore\FirestoreClient;

class EditBridgesAffected extends Component
{
    public $bridgeId;

    public $bridgename;
    public $bridgeAge;
    public $bridgeLength;
    public $latitude;
    public $longtitude;

    public function mount($id)
    {
        $this->bridgeId = $id;

        $db = $this->firestore();

        $doc = $db->collection('AffectedBridge')
            ->document($id)
            ->snapshot();

        if ($doc->exists()) {
            $data = $doc->data();

            $this->bridgename = $data['bridgename'] ?? '';
            $this->bridgeAge = $data['bridgeAge'] ?? '';
            $this->bridgeLength = $data['bridgeLength'] ?? '';
            $this->latitude = $data['latitude'] ?? '';
            $this->longtitude = $data['longtitude'] ?? '';
        } else {
            session()->flash('error', 'Bridge not found');
        }
    }

    public function firestore()
    {
        return new FirestoreClient([
            'keyFilePath' => storage_path('app/private/firebase-adminsdk.json'),
        ]);
    }

    public function update()
    {
        $this->validate([
            'bridgename' => 'required',
            'bridgeAge' => 'required',
            'bridgeLength' => 'required',
            'latitude' => 'required',
            'longtitude' => 'required',
        ]);

        try {
            $db = $this->firestore();

            $collection = $db->collection('AffectedBridges');

            $docs = $collection->documents();

            $newName = strtolower(trim($this->bridgename));

            foreach ($docs as $doc) {
                if (!$doc->exists()) continue;

                // skip current record
                if ($doc->id() == $this->bridgeId) continue;

                $data = $doc->data();

                $existingName = strtolower(trim($data['bridgename'] ?? ''));

                // 🔥 DUPLICATE CHECK (NAME ONLY)
                if ($existingName === $newName) {
                    session()->flash('error', 'Bridge name already exists.');
                    return;
                }
            }

            // UPDATE DATA
            $collection
                ->document($this->bridgeId)
                ->set([
                    'bridgename' => trim($this->bridgename),
                    'bridgeAge' => trim($this->bridgeAge),
                    'bridgeLength' => trim($this->bridgeLength),
                    'latitude' => trim($this->latitude),
                    'longtitude' => trim($this->longtitude),
                ], ['merge' => true]);

            session()->flash('message', 'Bridge updated successfully!');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.bridge-affected.edit-bridges-affected');
    }
}