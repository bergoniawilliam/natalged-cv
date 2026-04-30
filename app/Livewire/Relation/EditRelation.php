<?php

namespace App\Livewire\Relation;

use Livewire\Component;
use Kreait\Firebase\Factory;

class EditRelation extends Component
{
    public $relation_id;

    public $bridge_id = '';
    public $affected_type = '';
    public $affected_id = '';
    public $latitude = '';
    public $longtitude = '';

    public $bridges = [];
    public $affectedItems = [];

    public function render()
    {
        return view('livewire.relation.edit-relation');
    }

    protected function db()
    {
        return (new Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'))
            ->createFirestore()
            ->database();
    }

    public function mount($id)
    {
        $this->relation_id = $id;

        $db = $this->db();

        // 🔥 LOAD BRIDGES
        $documents = $db->collection('RefBridgeWaterlevel')->documents();

        foreach ($documents as $doc) {
            if (!$doc->exists()) continue;

            $data = $doc->data();

            $this->bridges[] = [
                'id' => $doc->id(),
                'label' => ($data['Bridge_name'] ?? 'No Name') . ' - Water Level ' . ($data['Water_lvl'] ?? '0'),
            ];
        }

        // 🔥 LOAD RELATION DATA
        $relation = $db->collection('Relation')
            ->document($id)
            ->snapshot();

        if ($relation->exists()) {
            $data = $relation->data();

            $this->bridge_id = $data['Refbridge_Waterlvl'] ?? '';
            $this->affected_id = $data['Affected_doc_id'] ?? '';

            // 🔥 AUTO DETECT TYPE
            if ($db->collection('AffectedRoads')->document($this->affected_id)->snapshot()->exists()) {
                $this->affected_type = 'AffectedRoads';
            } elseif ($db->collection('AffectedEvacuationCenter')->document($this->affected_id)->snapshot()->exists()) {
                $this->affected_type = 'AffectedEvacuationCenter';
            }

            // 🔥 LOAD ITEMS
            $this->loadAffectedItems();

            // 🔥 LOAD LAT LONG
            $this->updatedAffectedId();
        }
    }

    public function loadAffectedItems()
    {
        $this->affectedItems = [];

        if (!$this->affected_type) return;

        $documents = $this->db()
            ->collection($this->affected_type)
            ->documents();

        foreach ($documents as $doc) {
            if (!$doc->exists()) continue;

            $data = $doc->data();

            $name = $this->affected_type === 'AffectedRoads'
                ? ($data['Road_name'] ?? 'No Name')
                : ($data['Evac_name'] ?? 'No Name');

            $this->affectedItems[] = [
                'id' => $doc->id(),
                'name' => $name
            ];
        }
    }

    public function updatedAffectedType()
    {
        $this->affected_id = '';
        $this->latitude = '';
        $this->longtitude = '';

        $this->loadAffectedItems();
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

    public function update()
    {
        $this->validate([
            'bridge_id' => 'required',
            'affected_type' => 'required',
            'affected_id' => 'required',
        ]);

        $this->db()
            ->collection('Relation')
            ->document($this->relation_id)
            ->set([
                'Refbridge_Waterlvl' => $this->bridge_id,
                'Affected_doc_id' => $this->affected_id,
            ]);

        session()->flash('message', 'Relation updated successfully.');
    }
}