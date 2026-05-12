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
            'keyFile' => json_decode(env('FIREBASE_CREDENTIALS'), true),
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