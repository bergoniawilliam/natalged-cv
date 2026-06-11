<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\WithoutUrlPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Factory;
use Google\Cloud\Firestore\FirestoreClient;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Users extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $newPassword = '';
    public $confirmPassword = '';
    public $message = '';
    public $messageType = '';
    public $users = [];
    public $selectedCollection = 'Administrators';
    public $perPage = 10;
    public $query = '';
    public $firebase_collections = [
        'Administrators',
        'BatanesPPO',
        'CPPO',
        'IPPO',
        'NVPPO',
        'QPPO',
        'SCPO',
    ];
    public $isLocked = false;
    public $confirmingDelete = false;
    public $deleteId;

    protected $queryString = ['query'];

    public $Name = '';
    public $Rank = '';
    public $Role = '';
    public $email = '';
    public $password = '';
    public $ContactNo = '';
    public $Unit = '';
    public $SubUnit = '';
    public $Station = '';
    public $CallSign = '';
    public $Payslip = '';
    public $BWC = '';

    public $passwordUserId = null;

    public $showPasswordModal = false;

    public $selectedUserId = null;

    public $selectedUserName = null;

    public $selectedUserRank = null;

    public function mount()
    {
         $userCollection = auth()->user()->collection;

        $allCollections = [
            'Administrators',
            'BatanesPPO',
            'CPPO',
            'IPPO',
            'NVPPO',
            'QPPO',
            'SCPO',
        ];

        if ($userCollection === 'ALL') {

            $this->firebase_collections = $allCollections;
            $this->selectedCollection = 'Administrators';
            $this->isLocked = false;

        } else {

            $this->firebase_collections = [$userCollection];
            $this->selectedCollection = $userCollection;
            $this->isLocked = true;
        }
    }

    public function render()
    {
        return view('livewire.users.users');
    }

    public function addUser()
    {
        return redirect()->route('adduser');
    }

    // public function confirmDelete($id)
    // {
    //     $this->deleteId = $id;
    //     $this->emit('triggerDeleteConfirmation');
    // }

    // public function deleteUser($uid)
    // {
    //     $db = $this->firestore();
    //     $auth = $this->firebaseAuth();

    //     try {
    //         $db->collection($this->selectedCollection)->document($uid)->delete();
    //         $auth->deleteUser($uid);

    //         $this->resetPage();

    //         // Simple session flash lang
    //         session()->flash('message', 'User deleted successfully.');
    //     } catch (\Exception $e) {
    //         session()->flash('error', 'Error deleting user: ' . $e->getMessage());
    //     }
    // }

    public function setDeleteId($id)
    {
        $this->deleteId = $id;
    }
    public function deleteUser()
    {
        $db = $this->firestore();
        $auth = $this->firebaseAuth();

        try {
            if (!$this->deleteId) return;

            $db->collection($this->selectedCollection)
                ->document($this->deleteId)
                ->delete();

            $auth->deleteUser($this->deleteId);

            session()->flash('message', 'User deleted successfully.');

            $this->deleteId = null;

            $this->resetPage();

            return redirect()->route('users');

        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
    public function updatedSelectedCollection()
    {
        $this->resetPage();
    }

    public function updatingQuery()
    {
        $this->resetPage();
    }

    public function openChangePasswordModal($uid, $name = null, $rank = null)
    {
        $this->reset(['message','messageType','newPassword','confirmPassword']);

        $this->selectedUserId = $uid;
        $this->selectedUserName = $name;
        $this->selectedUserRank = $rank;

        $this->showPasswordModal = true;
    }
    public function closeChangePasswordModal()
    {
        $this->showPasswordModal = false;
    }
    public function changePassword()
    {
        if (!$this->selectedUserId) {
            $this->message = "No user selected.";
            $this->messageType = "error";
            return;
        }

        $this->validate([
            'newPassword' => 'required|min:8|same:confirmPassword'
        ]);

        try {
            $auth = $this->firebaseAuth();

            $auth->updateUser($this->selectedUserId, [
                'password' => $this->newPassword
            ]);

          session()->flash('message', 'Password updated successfully.');

            $this->reset([
                'newPassword',
                'confirmPassword',
                'selectedUserId',
                'selectedUserName'
            ]);

            $this->showPasswordModal = false;

        } catch (\Exception $e) {
            $this->message = "Password change failed: " . $e->getMessage();
            $this->messageType = "error";
        }
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
    // AUTH
    // =========================
    protected function firebaseAuth()
    {
        $credentials = json_decode(env('FIREBASE_CREDENTIALS'), true);

        $credentials['private_key'] = str_replace("\\n", "\n", $credentials['private_key']);

        return (new Factory)
            ->withServiceAccount($credentials)
            ->createAuth();
    }


    protected function firestoreToArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->firestoreToArray($value);
            } elseif ($value instanceof \Google\Cloud\Core\Timestamp) {
                $data[$key] = $value->get()->format('M d, Y');
            } elseif (method_exists($value, 'toDateTime')) {
                $data[$key] = $value->toDateTime()->format('M d, Y');
            } elseif (is_scalar($value) || $value === null) {
                $data[$key] = $value;
            } else {
                $data[$key] = null;
            }
        }
        return $data;
    }

    /**
     * Load users with server-side pagination
     */
    public function loadUsers($pageSize = null, $startAfter = null)
    {
        $db = $this->firestore();
        $pageSize = $pageSize ?? $this->perPage;

        $query = $db->collection($this->selectedCollection);

        // Prefix search for "Name"
        if ($this->query) {
            $query = $query->where('Name', '>=', $this->query)
                           ->where('Name', '<=', $this->query . "\uf8ff");
        }

        // Pagination
        if ($startAfter) {
            $query = $query->startAfter($startAfter);
        }

        $query = $query->limit($pageSize);
        $documents = $query->documents();

        $users = [];
        $lastDoc = null;

        foreach ($documents as $doc) {
            if (!$doc->exists()) continue;
            $data = $this->firestoreToArray($doc->data());
            $data['_id'] = $doc->id();
            $users[] = $data;
            $lastDoc = $doc;
        }

        return [
            'users' => $users,
            'lastDoc' => $lastDoc,
        ];
    }

    /**
     * Total users count for paginator
     */
    protected function countTotalUsers()
    {
        $db = $this->firestore();
        $documents = $db->collection($this->selectedCollection)->documents();
        $count = 0;
        foreach ($documents as $doc) {
            if ($doc->exists()) $count++;
        }
        return $count;
    }

    #[Computed]
    public function collections()
    {
        $db = $this->firestore();
        $query = $db->collection($this->selectedCollection);

        // Remove the Firestore where() query entirely
        $documents = $query->documents();

        $users = [];
        foreach ($documents as $doc) {
            if (!$doc->exists()) continue;
            $data = $this->firestoreToArray($doc->data());
            $data['_id'] = $doc->id();
            $users[] = $data;
        }

        // Filter in PHP — case-insensitive, partial match
       if ($this->query) {
            $search = strtolower($this->query);
            $users = array_values(array_filter($users, function ($user) use ($search) {
                $searchable = [
                    $user['Name'] ?? '',
                    $user['email'] ?? '',
                    $user['Rank'] ?? '',
                    $user['CallSign'] ?? '',
                    $user['Unit'] ?? '',
                    $user['SubUnit'] ?? '',
                    $user['Role'] ?? '',
                    $user['Station'] ?? '',
                    $user['ContactNo'] ?? '',
                    $user['Payslip'] ?? '',
                ];

                foreach ($searchable as $field) {
                    if (str_contains(strtolower($field), $search)) {
                        return true;
                    }
                }

                return false;
            }));
        }

        $page = $this->getPage();
        $perPage = $this->perPage;
        $total = count($users);
        $offset = ($page - 1) * $perPage;
        $items = array_slice($users, $offset, $perPage);

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }
    public function updatedPerPage()
    {
        $this->resetPage();
    }
    /**
     * CSV export
     */
    public function exportCsv(): StreamedResponse
    {
        $filename = 'users_' . $this->selectedCollection . '_' . now()->format('Ymd_His') . '.csv';
        $result = $this->loadUsers($this->perPage * 100); // fetch more for export
        $users = $result['users'];

        return response()->streamDownload(function () use ($users) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Email','Rank','Name','SubUnit','Unit','Role','Station','BWC',
                'CallSign','ContactNo','Payslip','Created At',
            ]);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user['email'] ?? '',
                    $user['Rank'] ?? '',
                    $user['Name'] ?? '',
                    $user['SubUnit'] ?? '',
                    $user['Unit'] ?? '',
                    $user['Role'] ?? '',
                    $user['Station'] ?? '',
                    $user['BWC'] ?? '',
                    $user['CallSign'] ?? '',
                    $user['ContactNo'] ?? '',
                    $user['Payslip'] ?? '',
                    $user['CreatedAt'] ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
    public function saveUser()
    {
        $this->validate([
            'Name'      => 'required|string',
            'Rank'      => 'required|string',
            'email'     => 'required|email',
            'password'  => 'required|min:8',
        ]);

        try {
            $auth = $this->firebaseAuth();
            $db   = $this->firestore();

            $createdUser = $auth->createUserWithEmailAndPassword($this->email, $this->password);
            $uid = $createdUser->uid;

            $db->collection($this->selectedCollection)->document($uid)->set([
                'Name'      => $this->Name,
                'Rank'      => $this->Rank,
                'Role'      => $this->Role,
                'email'     => $this->email,
                'ContactNo' => $this->ContactNo,
                'Unit'      => $this->Unit,
                'SubUnit'   => $this->SubUnit,
                'Station'   => $this->Station,
                'CallSign'  => $this->CallSign,
                'Payslip'   => $this->Payslip,
                'BWC'       => $this->BWC,
                'CreatedAt' => now()->toDateTimeString(),
            ]);
            session()->flash('message', 'User added successfully.');
            
            $this->reset([
                'Name','Rank','Role','email','password',
                'ContactNo','Unit','SubUnit','Station',
                'CallSign','Payslip','BWC',
            ]);

            return redirect()->route('users');
            

        } catch (\Exception $e) {
            session()->flash('error', 'Error adding user: ' . $e->getMessage());
        }
    }
  
}