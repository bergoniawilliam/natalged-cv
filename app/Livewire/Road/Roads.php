<?php

namespace App\Livewire\Road;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Livewire\Component;
use Google\Cloud\Firestore\FirestoreClient; 
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\WithoutUrlPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Factory;

class Roads extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $roads = [];
    public $selectedCollection = 'AffectedRoads';
    public $perPage = 10;
    public $query = '';
    public $firebase_collections = [
        'AffectedRoads',

    ];
    public function render()
    {
        return view('livewire.road.roads');
    }
     protected function firestore()
    {
         return new FirestoreClient([
        'keyFile' => json_decode(file_get_contents(config('firebase.credentials')), true),
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
    public function loadRoads()
    {
        $roads  = []; // clear previous data

        $db = $this->firestore();

        $fields = [
            'Road_name',
            'roadAddress',
            'latitude',
            'longtitude'
        ];

        // Query sa currently selected collection
        $query = $db->collection($this->selectedCollection)->select($fields);

        // Kapag may search, i-apply lang sa selected collection
        // if ($this->query) {
        //     $query = $query->limit(50);
        // }

        $documents = $query->documents();

        foreach ($documents as $doc) {
            if (!$doc->exists()) continue;
            $data = $this->firestoreToArray($doc->data());
            $data['name_lower_case'] = strtolower($data['Road_name'] ?? '');
            // Case-insensitive search using the new field
            if ($this->query && stripos($data['name_lower_case'], strtolower($this->query)) === false) {
                continue;
            }
            $data['_id'] = $doc->id();
            $roads[] = $data;

        }

        return $roads;   
    }
    #[Computed]
    public function collections()
    {
        $roads = collect($this->loadRoads() ?? []);

        // 🔎 Apply search filter FIRST
        if ($this->query !== '') {
            $roads = $roads->filter(function ($roads) {
                return str_contains(
                    strtolower($bridges['name_lower_case'] ?? ''),
                    strtolower($this->query)
                );
            });
        }

        $page = $this->getPage();

        return new LengthAwarePaginator(
            $roads->forPage($page, $this->perPage)->values(),
            $roads->count(),
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
   