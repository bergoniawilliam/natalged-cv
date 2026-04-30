<?php

namespace App\Livewire\BridgeAffected;

use Livewire\Component;
use Google\Cloud\Firestore\FirestoreClient;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\WithoutUrlPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class BridgesAffected extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $selectedCollection = 'AffectedBridge';
    public $perPage = 10;
    public $query = '';

    public $firebase_collections = [
        'AffectedBridge',
    ];

    public function render()
    {
        return view('livewire.bridge-affected.bridges-affected');
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
                $data[$key] = $value->get()->format('M d, Y');
            } elseif (method_exists($value, 'toDateTime')) {
                $data[$key] = $value->toDateTime()->format('M d, Y');
            } elseif (is_scalar($value) || $value === null) {
                $data[$key] = $value;
            } else {
                $data[$key] = null;
            }
        }
        return $data;
    }

    public function loadBridges()
    {
        $db = $this->firestore();
        $bridges = [];

        $fields = [
            'bridgename',
            'bridgeAge',
            'bridgeLength',
            'latitude',
            'longtitude'
        ];

        $query = $db->collection($this->selectedCollection)->select($fields);
        $documents = $query->documents();

        foreach ($documents as $doc) {
            if (!$doc->exists()) continue;

            $data = $this->firestoreToArray($doc->data());

            $data['_id'] = $doc->id();
            $data['search'] = strtolower($data['bridgename'] ?? '');

            if ($this->query && !str_contains($data['search'], strtolower($this->query))) {
                continue;
            }

            $bridges[] = $data;
        }

        return $bridges;
    }

    #[Computed]
    public function collections()
    {
        $data = collect($this->loadBridges());

        $page = $this->getPage();

        return new LengthAwarePaginator(
            $data->forPage($page, $this->perPage)->values(),
            $data->count(),
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

        session()->flash('message', 'Bridge deleted successfully!');
    }
}