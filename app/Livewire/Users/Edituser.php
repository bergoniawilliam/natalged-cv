<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Kreait\Firebase\Factory;
use Google\Cloud\Firestore\FirestoreClient;

class Edituser extends Component
{
    public $uid;
    public $selectedCollection;
    public $isLocked;

    public $BWC;
    public $CallSign;
    public $ContactNo;
    public $Name;
    public $Payslip;
    public $Rank;
    public $Role;
    public $Station;
    public $SubUnit;
    public $Unit;
    public $email;

    public $firebase_collections = [
        'Administrators',
        'BatanesPPO',
        'CPPO',
        'IPPO',
        'NVPPO',
        'QPPO',
        'SCPO',
    ];

    protected $rules = [
        'BWC' => 'required|string',
        'CallSign' => 'required|string',
        'ContactNo' => 'required|string',
        'Name' => 'required|string',
        'Payslip' => 'required|string',
        'Rank' => 'required|string',
        'Role' => 'required|string',
        'Station' => 'required|string',
        'SubUnit' => 'required|string',
        'Unit' => 'required|string',
        'email' => 'required|email',
    ];

    // =========================
    // MOUNT
    // =========================
    public function mount($uid, $collection)
    {
        $this->uid = $uid;
        $this->selectedCollection = $collection;

        $this->isLocked = auth()->user()->collection !== 'ALL';

        $db = $this->firestore();

        $doc = $db->collection($collection)
            ->document($uid)
            ->snapshot();

        if (!$doc->exists()) {
            abort(404, 'User not found');
        }

        $data = $doc->data();

        $this->BWC = $data['BWC'] ?? '';
        $this->CallSign = $data['CallSign'] ?? '';
        $this->ContactNo = $data['ContactNo'] ?? '';
        $this->Name = $data['Name'] ?? '';
        $this->Payslip = $data['Payslip'] ?? '';
        $this->Rank = $data['Rank'] ?? '';
        $this->Role = $data['Role'] ?? '';
        $this->Station = $data['Station'] ?? '';
        $this->SubUnit = $data['SubUnit'] ?? '';
        $this->Unit = $data['Unit'] ?? '';
        $this->email = $data['email'] ?? '';
    }

    // =========================
    // UPDATE USER
    // =========================
    public function updateUser()
    {
        $this->validate();

        $authCollection = auth()->user()->collection;

        // LOCK logic same as AddUser
        if ($authCollection !== 'ALL') {
            $this->selectedCollection = $authCollection;
        }

        $collection = $this->selectedCollection;

        $db = $this->firestore();

        $db->collection($collection)
            ->document($this->uid)
            ->update([
                ['path' => 'BWC', 'value' => $this->BWC],
                ['path' => 'CallSign', 'value' => $this->CallSign],
                ['path' => 'ContactNo', 'value' => $this->ContactNo],
                ['path' => 'Name', 'value' => $this->Name],
                ['path' => 'Payslip', 'value' => $this->Payslip],
                ['path' => 'Rank', 'value' => $this->Rank],
                ['path' => 'Role', 'value' => $this->Role],
                ['path' => 'Station', 'value' => $this->Station],
                ['path' => 'SubUnit', 'value' => $this->SubUnit],
                ['path' => 'Unit', 'value' => $this->Unit],
                ['path' => 'email', 'value' => $this->email],
            ]);

        // Firebase Auth update
        try {
            $auth = $this->firebaseAuth();

            $auth->updateUser($this->uid, [
                'email' => $this->email,
                'displayName' => $this->Name,
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Auth update error: ' . $e->getMessage());
            return;
        }

        session()->flash('message', 'User updated successfully!');
    }

    // =========================
    // FIRESTORE
    // =========================
    protected function firestore(): FirestoreClient
    {
        $credentials = json_decode(env('FIREBASE_CREDENTIALS'), true);
        $credentials['private_key'] = str_replace("\\n", "\n", $credentials['private_key']);

        return new FirestoreClient([
            'keyFile' => $credentials,
        ]);
    }

    // =========================
    // FIREBASE AUTH
    // =========================
    protected function firebaseAuth()
    {
        $credentials = json_decode(env('FIREBASE_CREDENTIALS'), true);
        $credentials['private_key'] = str_replace("\\n", "\n", $credentials['private_key']);

        return (new Factory)
            ->withServiceAccount($credentials)
            ->createAuth();
    }

    public function render()
    {
        return view('livewire.users.edituser');
    }
}