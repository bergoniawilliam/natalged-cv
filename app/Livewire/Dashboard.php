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
        $credentials = json_decode(env('FIREBASE_CREDENTIALS'), true);

        $credentials['private_key'] = str_replace('\n', "\n", $credentials['private_key']);

        $db = new FirestoreClient([
            'keyFile' => $credentials,
        ]);

        foreach ($this->firebase_collections as $collection) {
            $this->counts[$collection] = iterator_count(
                $db->collection($collection)->documents()
            );
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}