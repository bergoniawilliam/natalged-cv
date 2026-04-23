<?php

namespace App\Livewire\Evacuation;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Livewire\Component;
use Google\Cloud\Firestore\FirestoreClient; 
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\WithoutUrlPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Factory;

class Evacuation extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $evacuation = [];
    public $selectedCollection = 'AffectedEvacuationCenter';
    public $perPage = 10;
    public $query = '';
    public $firebase_collections = [
        'AffectedEvacuationCenter',

    ];

    public function render()
    {
        return view('livewire.evacuation.evacuation');
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
   public function loadevacuation()
    {
        $evacuation  = []; // clear previous data

        $db = $this->firestore();

        $fields = [
            'Evac_name',
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
            $data['name_lower_case'] = strtolower($data['Evac_name'] ?? '');
            // Case-insensitive search using the new field
            if ($this->query && stripos($data['name_lower_case'], strtolower($this->query)) === false) {
                continue;
            }
            $data['_id'] = $doc->id();
            $evacuation[] = $data;

        }

        return $evacuation;   
    }
    #[Computed]
    public function collections()
    {
        $evacuation = collect($this->loadevacuation() ?? []);

        // 🔎 Apply search filter FIRST
        if ($this->query !== '') {
            $evacuation = $evacuation->filter(function ($evacuation) {
                return str_contains(
                    strtolower($bridges['name_lower_case'] ?? ''),
                    strtolower($this->query)
                );
            });
        }

        $page = $this->getPage();

        return new LengthAwarePaginator(
            $evacuation->forPage($page, $this->perPage)->values(),
            $evacuation->count(),
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

        $this->dispatch('showAlert', 'Deleted successfully!');
    }
}
 