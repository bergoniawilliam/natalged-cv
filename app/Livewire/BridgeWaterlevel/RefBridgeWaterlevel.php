<?php

namespace App\Livewire\BridgeWaterlevel;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Livewire\Component;
use Google\Cloud\Firestore\FirestoreClient; 
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\WithoutUrlPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Factory;

class RefBridgeWaterlevel extends Component 
{
    use WithPagination, WithoutUrlPagination;
    public $bridges = [];
    public $selectedCollection = 'RefBridgeWaterlevel';
    public $perPage = 10;
    public $query = '';
    public $firebase_collections = [
        'RefBridgeWaterlevel',

    ];

    public function render()
    {
        return view('livewire.bridge-waterlevel.ref-bridge-waterlevel');
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
    public function loadBridges()
    {
        $bridges  = []; // clear previous data

        $db = $this->firestore();

        $fields = [
            'Bridge_name','Water_lvl'
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
            $data['name_lower_case'] = strtolower($data['Bridge_name'] ?? '');
            // Case-insensitive search using the new field
            if ($this->query && stripos($data['name_lower_case'], strtolower($this->query)) === false) {
                continue;
            }
            $data['_id'] = $doc->id();
            $bridges[] = $data;

        }

        return $bridges;
        
    }
    #[Computed]
    public function collections()
    {
        $bridges = collect($this->loadBridges() ?? []);

        // 🔎 Apply search filter FIRST
        if ($this->query !== '') {
            $bridges = $bridges->filter(function ($bridges) {
                return str_contains(
                    strtolower($bridges['name_lower_case'] ?? ''),
                    strtolower($this->query)
                );
            });
        }

        $page = $this->getPage();

        return new LengthAwarePaginator(
            $bridges->forPage($page, $this->perPage)->values(),
            $bridges->count(),
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
