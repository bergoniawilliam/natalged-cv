<?php

namespace App\Livewire;

use Livewire\Component;
use Google\Cloud\Firestore\FirestoreClient;

class Dashboard extends Component
{
    public $firebase_collections = [
        'Administrators',
        'BatanesPPO',
        'CPPO',
        'IPPO',
        'NVPPO',
        'QPPO',
        'SCPO',
    ];

    public $counts = [];

    public function mount()
    {
        $db = new FirestoreClient([
            'keyFilePath' => storage_path('app/private/firebase-adminsdk.json'),
        ]);

        foreach ($this->firebase_collections as $collection) {
            $this->counts[$collection] = $db
                ->collection($collection)
                ->documents()
                ->size(); // count documents
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}