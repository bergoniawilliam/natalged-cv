<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Kreait\Firebase\Factory;

class Edituser extends Component
{
    public $uid;

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
    public $selectedCollection;

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

    public function mount($collection, $uid)
    {
        $this->uid = $uid;
        $this->selectedCollection = $collection;

        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

        $firestore = $factory->createFirestore();
        $db = $firestore->database();

        $doc = $db->collection($collection)->document($uid)->snapshot();

        if (!$doc->exists()) {
            abort(404, 'User not found');
        }

        $data = $doc->data();

        // 🔄 Populate fields
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

    public function updateUser()
    {
        $this->validate();

        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

        // 🔥 1. Update Firestore
        $firestore = $factory->createFirestore();
        $db = $firestore->database();

        $db->collection($this->selectedCollection)
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

        // 🔥 2. Update Firebase Auth (email + name)
        $auth = $factory->createAuth();

        try {
            $auth->updateUser($this->uid, [
                'email' => $this->email,
                'displayName' => $this->Name,
            ]);
        } catch (\Exception $e) {
            session()->flash('message', 'Auth update error: ' . $e->getMessage());
            return;
        }

        session()->flash('message', 'User updated successfully!');
    }

    public function render()
    {
        return view('livewire.users.edituser');
    }
}