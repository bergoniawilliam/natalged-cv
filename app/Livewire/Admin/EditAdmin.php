<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Google\Cloud\Firestore\FirestoreClient;

class EditAdmin extends Component
{
    public $userId;

    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $password;
    public $rank;
    public $selectedCollection;

    public $role = null;      // selected role
    public $roles = [];       // dropdown roles

    public function mount($id)
    {
        $user = User::with('roles')->findOrFail($id);

        $this->userId = $user->id;

        $this->first_name = $user->first_name;
        $this->middle_name = $user->middle_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->rank = $user->rank;
        $this->selectedCollection = $user->collection;

        // current role
        $this->role = $user->roles->first()?->name;

        // ALL roles for dropdown
        $this->roles = Role::pluck('name')->toArray();
    }

    public function update()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'rank' => 'required',
        ]);

        $user = User::findOrFail($this->userId);

        $data = [
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'rank' => $this->rank,
            'collection' => $this->selectedCollection,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        // ROLE SYNC (Spatie)
        if ($this->role) {
            $user->syncRoles([$this->role]);
        }

        $this->password = '';

        session()->flash('success', 'Admin updated successfully!');
    }

    // Firebase collections
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

        $credentials['private_key'] = str_replace("\\n", "\n", $credentials['private_key']);

        return new FirestoreClient([
            'keyFile' => $credentials,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.edit-admin');
    }
}