<?php

namespace App\Livewire\Relation;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Livewire\Component;
use Google\Cloud\Firestore\FirestoreClient; 
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\WithoutUrlPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Factory;

class Relation extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $relation = [];
    public $selectedCollection = 'Relation';
    public $perPage = 10;
    public $query = '';
    public $firebase_collections = [
        'Relation',

    ];

    public function render()
    {
        return view('livewire.relation.relation');
    }

     protected function firestore()
    {
        return new FirestoreClient([
            'keyFilePath' => storage_path('app/private/firebase-adminsdk.json'),
        ]);
    }
    protected function firestoreToArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->firestoreToArray($value);
            } elseif ($value instanceof \Google\Cloud\Core\Timestamp) {
                $data[$key] = $value->get()->format('M d, Y'); // Google SDK
            } elseif (method_exists($value, 'toDateTime')) {
                $data[$key] = $value->toDateTime()->format('M d, Y'); // Kreait SDK
            } elseif (is_scalar($value) || $value === null) {
                $data[$key] = $value;
            } else {
                // Skip other Firestore objects to avoid recursion
                $data[$key] = null;
            }
        }
        return $data;
    }
    public function loadRelation()
    {
            $relation = [];

            $db = $this->firestore();

            $fields = [
                'Refbridge_Waterlvl',
                'Affected_doc_id'
            ];

            $query = $db->collection($this->selectedCollection)->select($fields);
            $documents = $query->documents();

            foreach ($documents as $doc) {
                if (!$doc->exists()) continue;

                $data = $this->firestoreToArray($doc->data());
                $data['_id'] = $doc->id();

                // =========================
                // 🔥 BRIDGE DETAILS
                // =========================
                $bridgeSnap = $db->collection('RefBridgeWaterlevel')
                    ->document($data['Refbridge_Waterlvl'])
                    ->snapshot();

                $data['bridge_name'] = $bridgeSnap->exists()
                    ? ($bridgeSnap['Bridge_name'] ?? 'N/A')
                    : 'N/A';

                $data['water_lvl'] = $bridgeSnap->exists()
                    ? ($bridgeSnap['Water_lvl'] ?? 'N/A')
                    : 'N/A';

                // =========================
                // 🔥 AFFECTED DETAILS (Road or Evac)
                // =========================
                $data['affected_name'] = 'N/A'; // default

                // try Roads
                $roadSnap = $db->collection('AffectedRoads')
                    ->document($data['Affected_doc_id'])
                    ->snapshot();

                if ($roadSnap->exists()) {
                    $data['affected_name'] = $roadSnap['Road_name'] ?? 'N/A';
                } else {
                    // try Evacuation
                    $evacSnap = $db->collection('AffectedEvacuationCenter')
                        ->document($data['Affected_doc_id'])
                        ->snapshot();

                    if ($evacSnap->exists()) {
                        $data['affected_name'] = $evacSnap['Evac_name'] ?? 'N/A';
                    }
                }

                // =========================
                // 🔍 SEARCH FIX (NO ERROR)
                // =========================
                $data['name_lower_case'] = strtolower(
                    trim(
                        ($data['bridge_name'] ?? '') . ' ' .
                        ($data['affected_name'] ?? '') . ' ' .
                        ($data['water_lvl'] ?? '')
                    )
                );

                if ($this->query && stripos($data['name_lower_case'], strtolower($this->query)) === false) {
                    continue;
                }

                $relation[] = $data;
            }

            return $relation;
        }       
    #[Computed]
    public function collections()
    {
        $relation = collect($this->loadRelation() ?? []);

        // 🔎 Apply search filter FIRST
        if ($this->query !== '') {
            $relation = $relation->filter(function ($relation) {
                return str_contains(
                    strtolower($relation['name_lower_case'] ?? ''),
                    strtolower($this->query)
                );
            });
        }

        $page = $this->getPage();

        return new LengthAwarePaginator(
            $relation->forPage($page, $this->perPage)->values(),
            $relation->count(),
            $this->perPage,
            $page,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
    }
    public function deleteBridge($id)
    {
        $this->firestore()
            ->collection($this->selectedCollection)
            ->document($id)
            ->delete();
        session()->flash('message', 'Evacuation Center Deleted successfully!');
        $this->dispatch('showAlert', 'Deleted successfully!');
    }

  
} 