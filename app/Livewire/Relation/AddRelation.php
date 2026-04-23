<?php

namespace App\Livewire\Relation;

use Livewire\Component;
use Kreait\Firebase\Factory;

class AddRelation extends Component
{
    public $bridge_id = '';
    public $affected_type = '';
    public $affected_id = '';
    public $latitude = '';
    public $longtitude = '';

    public $bridges = [];
    public $affectedItems = [];

    public function render()
    {
        return view('livewire.relation.add-relation');
    }

    protected function db()
    {
        return (new Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'))
            ->createFirestore()
            ->database();
    }

    public function mount()
    {
        $db = $this->db();

        // LOAD BRIDGES
        $documents = $db->collection('RefBridgeWaterlevel')->documents();

        foreach ($documents as $doc) {
            if (!$doc->exists()) continue;

            $data = $doc->data();

            $this->bridges[] = [
                'id' => $doc->id(),
                'label' => ($data['Bridge_name'] ?? 'No Name') . ' - Water Level ' . ($data['Water_lvl'] ?? '0'),
            ];
        }
    }

    public function updatedAffectedType()
    {
        $this->affectedItems = [];
        $this->affected_id = '';
        $this->latitude = '';
        $this->longtitude = '';

        if (!$this->affected_type) return;

        $db = $this->db();

        $documents = $db->collection($this->affected_type)->documents();

        foreach ($documents as $doc) {
            if (!$doc->exists()) continue;

            $data = $doc->data();

            if ($this->affected_type === 'AffectedRoads') {
                $name = $data['Road_name'] ?? 'No Name';
            } elseif ($this->affected_type === 'AffectedEvacuationCenter') {
                $name = $data['Evac_name'] ?? 'No Name';
            } else {
                $name = 'No Name';
            }

            $this->affectedItems[] = [
                'id' => $doc->id(),
                'name' => $name
            ];
        }
    }
    public function updatedAffectedId()
    {
        $this->latitude = '';
        $this->longtitude = '';

        if (!$this->affected_id || !$this->affected_type) return;

        $doc = $this->db()
            ->collection($this->affected_type)
            ->document($this->affected_id)
            ->snapshot();

        if ($doc->exists()) {
            $data = $doc->data();

            $this->latitude = $data['latitude'] ?? '';
            $this->longtitude = $data['longtitude'] ?? '';
        }
    }
    public function save()
    {
        $this->validate([
            'bridge_id' => 'required',
            'affected_type' => 'required',
            'affected_id' => 'required',
        ]);

        $db = $this->db();

        $db->collection('Relation')->add([
            'Refbridge_Waterlvl' => $this->bridge_id,
            'Affected_doc_id' => $this->affected_id,
        ]);

        session()->flash('message', 'Relation saved successfully.');

        $this->reset([
            'bridge_id',
            'affected_type',
            'affected_id',
            'latitude',
            'longtitude'
        ]);
        $this->affectedItems = [];
    }
}