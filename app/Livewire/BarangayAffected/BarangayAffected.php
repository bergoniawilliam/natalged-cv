<?php

namespace App\Livewire\BarangayAffected;

use Livewire\Component;
use Google\Cloud\Firestore\FirestoreClient;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\WithoutUrlPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class BarangayAffected extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $selectedCollection = 'AffectedBarangay'; // ⚠️ siguraduhin tama
    public $perPage = 10;
    public $query = '';

    public function render()
    {
        return view('livewire.barangay-affected.barangay-affected');
    }

    protected function firestore()
    {
        return new FirestoreClient([
            'keyFilePath' => storage_path('app/private/firebase-adminsdk.json'),
        ]);
    }

    public function loadBarangays()
    {
        $db = $this->firestore();
        $barangays = [];

        $documents = $db->collection($this->selectedCollection)->documents();

        foreach ($documents as $doc) {
            if (!$doc->exists()) continue;

            $data = $doc->data();

            $data['_id'] = $doc->id();
            $data['search'] = strtolower($data['barangayName'] ?? '');

            // 🔍 search
            if ($this->query && !str_contains($data['search'], strtolower($this->query))) {
                continue;
            }

            $barangays[] = $data;
        }

        return $barangays;
    }

    #[Computed]
    public function collections()
    {
        $data = collect($this->loadBarangays());

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

    public function deleteBarangay($id)
    {
        try {
            $this->firestore()
                ->collection($this->selectedCollection)
                ->document($id)
                ->delete();

            session()->flash('message', 'Barangay deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}