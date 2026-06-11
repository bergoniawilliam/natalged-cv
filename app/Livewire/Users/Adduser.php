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
    public $isLocked;

    public $selectedCollection;

    public $firebase_collections = [
        'Administrators',
        'BatanesPPO',
        'CPPO',
        'IPPO',
        'NVPPO',
        'QPPO',
        'SCPO',
    ];

    public function mount()
    {
        $this->selectedCollection = auth()->user()->collection;
        $this->isLocked = auth()->user()->collection !== 'ALL';
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
        'selectedCollection' => 'required|string',
    ];

    public function saveUser()
    {
        $this->validate();

        $authCollection = auth()->user()->collection;

        if ($authCollection !== 'ALL') {
            $this->selectedCollection = $authCollection;
        }

        if ($authCollection !== 'ALL' && $this->selectedCollection !== $authCollection) {
            abort(403);
        }

        $credentials = json_decode(env('FIREBASE_CREDENTIALS'), true);
        $credentials['private_key'] = str_replace("\\n", "\n", $credentials['private_key']);

        $factory = (new Factory)->withServiceAccount($credentials);

        $auth = $factory->createAuth();

        try {
            $user = $auth->createUser([
                'email' => $this->email,
                'password' => $this->password,
                'displayName' => $this->Name,
            ]);

            $uid = $user->uid;

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return;
        }

        $db = $factory->createFirestore()->database();

        $payload = [
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
        ];

        if ($this->selectedCollection === 'ALL') {
            foreach (array_slice($this->firebase_collections, 1) as $collection) {
                $db->collection($collection)->document($uid)->set($payload);
            }
        } else {
            $db->collection($this->selectedCollection)->document($uid)->set($payload);
        }

        session()->flash('message', 'User added successfully!');

        $this->reset([
            'BWC','CallSign','ContactNo','Name','Payslip',
            'Rank','Role','Station','SubUnit','Unit',
            'email','password','selectedCollection'
        ]);
    }

    public function render()
    {
        return view('livewire.users.adduser');
    }
    public function getIsLockedProperty()
    {
        return auth()->user()->collection !== 'ALL';
    }
}