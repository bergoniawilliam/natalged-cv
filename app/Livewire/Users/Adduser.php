<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Kreait\Firebase\Factory;

class Adduser extends Component
{
    public function render()
    {
        return view('livewire.users.adduser');
    }
    public $BWC = 'None';
    public $CallSign = 'NoneF';
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
    public $selectedCollection = 'Administrators';

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
        'password' => 'required|string|min:6',
        'selectedCollection' => 'required|string',
    ];

   
   
    public function saveUser()
    {
        $this->validate();

        // Firebase connection
        $factory = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

        // 1️⃣ Create Auth user
        $auth = $factory->createAuth();

        try {
            $createdUser = $auth->createUser([
                'email' => $this->email,
                'password' => $this->password,
                'displayName' => $this->Name,
            ]);

            $uid = $createdUser->uid; // 🔑 UID ng bagong Auth user

        } catch (\Kreait\Firebase\Exception\Auth\EmailExists $e) {
            session()->flash('message', 'Error: Email already exists in Firebase Auth.');
            return;
        } catch (\Exception $e) {
            session()->flash('message', 'Firebase Auth error: ' . $e->getMessage());
            return;
        }

        // 2️⃣ Save user info to Firestore collection gamit ang UID bilang document ID
        $firestore = $factory->createFirestore();
        $db = $firestore->database();

        $db->collection($this->selectedCollection)
        ->document($uid) // 🔑 Firestore document ID = Auth UID
        ->set([
            'uid' => $uid, // link sa Firebase Auth
            'BWC' => $this->BWC,
            'CallSign' => $this->CallSign,
            'ContactNo' => $this->ContactNo,
            'CreatedAt' => now()->toDateTimeString(),
            'Name' => $this->Name,
            'Payslip' => $this->Payslip,
            'Rank' => $this->Rank,
            'Role' => $this->Role,
            'Station' => $this->Station,
            'SubUnit' => $this->SubUnit,
            'Unit' => $this->Unit,
            'email' => $this->email,
        ]);

        // 3️⃣ Success message
        session()->flash('message', 'User added successfully!');

        // 4️⃣ Reset form fields
        $this->reset([
            'BWC','CallSign','ContactNo','Name','Payslip','Rank','Role','Station','SubUnit','Unit','email','password'
        ]);
    }
    
}
