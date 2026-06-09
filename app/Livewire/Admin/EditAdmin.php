<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;

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

    public $firebaseCollections = [];

    public function mount($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->first_name = $user->first_name;
        $this->middle_name = $user->middle_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->rank = $user->rank;
        $this->selectedCollection = $user->collection;

        // kung galing Firebase or service mo ito
        $this->firebaseCollections = $this->getCollections();
    }

    public function getCollections()
    {
        return [
            'collection1',
            'collection2',
            'collection3',
        ];
    }

    public function update()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'rank' => 'required',
        ]);

        $user = User::findOrFail($this->userId);

        $user->update([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'rank' => $this->rank,
            'collection' => $this->selectedCollection,
        ]);

        if ($this->password) {
            $user->update([
                'password' => bcrypt($this->password),
            ]);
        }

        session()->flash('success', 'Admin updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.edit-admin');
    }
}