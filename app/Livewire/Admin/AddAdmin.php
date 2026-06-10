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
    public $email;
    public $password;

    public $selectedCollection;
    public $role = 'Admin';

    public function save()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'rank' => 'required',
            'selectedCollection' => 'required',
            'role' => 'required',
        ]);

        $user = User::create([
            'rank' => $this->rank,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'collection' => $this->selectedCollection,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole($this->role);

        session()->flash('success', 'User created successfully!');

        $this->reset([
            'first_name',
            'middle_name',
            'last_name',
            'rank',
            'email',
            'password',
            'selectedCollection',
        ]);

        $this->role = 'Admin';
    }

    #[Computed]
    public function firebaseCollections()
    {
        $collections = ['ALL'];

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