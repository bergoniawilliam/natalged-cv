<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;
use Kreait\Firebase\Factory;

class Deleteuser extends Component
{
    public $selectedCollection;

    // public function deleteUser($id)
    // {
    //     $factory = (new Factory)
    //         ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

    //     $db = $factory->createFirestore();
    //     $auth = $factory->createAuth();

    //     try {
    //         $db->collection($this->selectedCollection)->document($id)->delete();
    //         $auth->deleteUser($id);

    //         // SIMPLE SESSION FLASH INSTEAD OF ALPINE TOAST
    //         session()->flash('message', 'User deleted successfully');

    //         // Optional: refresh page or users list
    //         $this->emit('refreshUsers');

    //     } catch (\Exception $e) {
    //         session()->flash('error', 'Error deleting user: ' . $e->getMessage());
    //     }
    // }

    public function render()
    {
        return view('livewire.users.deleteuser');
    }
}