<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Hash;
use Google\Cloud\Firestore\FirestoreClient;

class AddAdmin extends Component
{
    public $first_name;
    public $middle_name;
    public $last_name;
    public $rank;
    public $collection;
    public $email;
    public $password;
    public $role = 'Admin';

    public $selectedCollection; // ✅ ADD THIS

    public function save()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'rank' => $this->rank,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'collection' => $this->selectedCollection, // optional fix
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        if (method_exists($user, 'assignRole')) {
            $user->assignRole($this->role);
        }

        session()->flash('success', 'Admin created successfully!');

        $this->reset();
    }

    #[Computed]
    public function firebaseCollections()
    {
        $collections = [];

        foreach ($this->firestore()->collections() as $collection) {
            $collections[] = $collection->id();
        }

        return $collections;
    }

    protected function firestore(): FirestoreClient
    {
        $credentials = json_decode(env('FIREBASE_CREDENTIALS'), true);

        $credentials['private_key'] = str_replace(
            "\\n",
            "\n",
            $credentials['private_key']
        );

        return new FirestoreClient([
            'keyFile' => $credentials,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.add-admin');
    }
}