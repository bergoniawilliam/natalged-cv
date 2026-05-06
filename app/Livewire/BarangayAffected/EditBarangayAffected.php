<?php

namespace App\Livewire\BarangayAffected;

use Livewire\Component;
use Google\Cloud\Firestore\FirestoreClient;

class EditBarangayAffected extends Component
{
    public $barangayId;

    public $barangayName;
    public $familiesAffected;
    public $latitude;
    public $longtitude;

    public function mount($id)
    {
        $this->barangayId = $id;

        $doc = $this->firestore()
            ->collection('AffectedBarangay')
            ->document($id)
            ->snapshot();

        if ($doc->exists()) {
            $data = $doc->data();

            $this->barangayName = $data['barangayName'] ?? '';
            $this->familiesAffected = $data['familiesAffected'] ?? '';
            $this->latitude = $data['latitude'] ?? '';
            $this->longtitude = $data['longtitude'] ?? '';
        } else {
            session()->flash('error', 'Barangay not found');
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
            'barangayName' => 'required',
            'familiesAffected' => 'required',
            'latitude' => 'required',
            'longtitude' => 'required',
        ]);

        try {
            $db = $this->firestore();
            $collection = $db->collection('AffectedBarangay');

            // 🔥 DUPLICATE CHECK (exclude sarili)
            $docs = $collection->documents();

            foreach ($docs as $doc) {
                if (!$doc->exists()) continue;

                if ($doc->id() == $this->barangayId) continue;

                $data = $doc->data();

                if (
                    strtolower(trim($data['barangayName'] ?? '')) === strtolower(trim($this->barangayName))
                ) {
                    session()->flash('error', 'Duplicate barangay already exists.');
                    return;
                }
            }

            // ✅ UPDATE
            $collection->document($this->barangayId)->set([
                'barangayName' => $this->barangayName,
                'familiesAffected' => $this->familiesAffected,
                'latitude' => $this->latitude,
                'longtitude' => $this->longtitude,
            ], ['merge' => true]);

            session()->flash('message', 'Barangay updated successfully!');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.barangay-affected.edit-barangay-affected');
    }
}