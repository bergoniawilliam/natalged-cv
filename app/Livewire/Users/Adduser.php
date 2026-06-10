<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Kreait\Firebase\Factory;

class AddUser extends Component
{
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
    public $password;

    public $selectedCollection;

    public function mount()
    {
        // 🔐 FORCE USER'S ASSIGNED COLLECTION
        $this->selectedCollection = auth()->user()->collection;
    }

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
        'password' => 'required|string|min:6',
    ];

    public function saveUser()
    {
        $this->validate();

        $collection = auth()->user()->collection; // 🔐 FIXED COLLECTION

        $credentials = json_decode(env('FIREBASE_CREDENTIALS'), true);
        $credentials['private_key'] = str_replace("\\n", "\n", $credentials['private_key']);

        $factory = (new Factory)->withServiceAccount($credentials);

        $auth = $factory->createAuth();

        try {
            $createdUser = $auth->createUser([
                'email' => $this->email,
                'password' => $this->password,
                'displayName' => $this->Name,
            ]);

            $uid = $createdUser->uid;

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return;
        }

        $firestore = $factory->createFirestore();
        $db = $firestore->database();

        // 🔐 SAVE ONLY TO USER'S COLLECTION
        $db->collection($collection)
            ->document($uid)
            ->set([
                'uid' => $uid,
                'BWC' => $this->BWC,
                'CallSign' => $this->CallSign,
                'ContactNo' => $this->ContactNo,
                'Name' => $this->Name,
                'Payslip' => $this->Payslip,
                'Rank' => $this->Rank,
                'Role' => $this->Role,
                'Station' => $this->Station,
                'SubUnit' => $this->SubUnit,
                'Unit' => $this->Unit,
                'email' => $this->email,
                'CreatedAt' => now()->toDateTimeString(),
            ]);

        session()->flash('message', 'User added successfully!');

        $this->reset([
            'BWC','CallSign','ContactNo','Name','Payslip',
            'Rank','Role','Station','SubUnit','Unit','email','password'
        ]);
    }

    public function render()
    {
        return view('livewire.users.adduser');
    }
}