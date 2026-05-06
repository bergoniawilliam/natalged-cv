<?php

namespace App\Livewire\BarangayAffected;

use Livewire\Component;
use Kreait\Firebase\Factory;

class AddBarangayAffected extends Component
{
    public $barangayName, $familiesAffected, $latitude, $longtitude;

    public function render()
    {
        return view('livewire.barangay-affected.add-barangay-affected');
    }

    public function save()
    {
        $this->validate([
            'barangayName' => 'required',
            'familiesAffected' => 'required',
            'latitude' => 'required',
            'longtitude' => 'required',
        ]);

        $db = (new Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'))
            ->createFirestore()
            ->database();

        try {

            // 🔥 DUPLICATE CHECK (barangay name lang)
            $docs = $db->collection('AffectedBarangay')->documents();

            foreach ($docs as $doc) {
                if (!$doc->exists()) continue;

                $data = $doc->data();

                if (
                    strtolower(trim($data['barangayName'] ?? '')) === strtolower(trim($this->barangayName))
                ) {
                    session()->flash('error', 'Barangay already exists.');
                    return;
                }
            }

            // ✅ SAVE
            $db->collection('AffectedBarangay')->add([
                'barangayName' => trim($this->barangayName),
                'familiesAffected' => trim($this->familiesAffected),
                'latitude' => trim($this->latitude),
                'longtitude' => trim($this->longtitude),
            ]);

            $this->reset();

            session()->flash('message', 'Barangay added successfully!');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}