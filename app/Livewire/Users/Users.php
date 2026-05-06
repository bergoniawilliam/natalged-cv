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
    public $confirmingDelete = false;
    public $deleteId;

    protected $queryString = ['query'];

    public function mount()
    {
        // no pre-loading to reduce read costs
    }

    public function render()
    {
        return view('livewire.users.users');
    }

    public function addUser()
    {
        return redirect()->route('adduser');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->emit('triggerDeleteConfirmation');
    }

    public function deleteUser($uid)
    {
        $db = $this->firestore();
        $auth = $this->firebaseAuth();

        try {
            $db->collection($this->selectedCollection)->document($uid)->delete();
            $auth->deleteUser($uid);

            $this->resetPage();

            // Simple session flash lang
            session()->flash('message', 'User deleted successfully.');
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

    public function openChangePasswordModal()
    {
        $this->reset(['message','messageType','newPassword','confirmPassword']);
    }

    public function changePassword($uid)
    {
        $this->validate([
            'newPassword' => 'required|min:8|same:confirmPassword'
        ]);

        try {
            $auth = $this->firebaseAuth();
            $auth->updateUser($uid, ['password' => $this->newPassword]);

            $this->reset(['newPassword','confirmPassword']);
            $this->message = "Password changed successfully.";
            $this->messageType = "success";
            $this->dispatch('close-password-modal');
        } catch (\Exception $e) {
            $this->message = "Password change failed.";
            $this->messageType = "error";
        }
    }

    protected function firestore(): FirestoreClient
    {
         return new FirestoreClient([
            'keyFilePath' => storage_path('app/private/firebase-adminsdk.json'),
        ]);
    }

    protected function firebaseAuth(): Auth
    {
        

        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));

        return $factory->createAuth();
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
        $page = $this->getPage();
        $pageSize = $this->perPage;

        $startAfter = session()->get("users_start_after_page_{$page}", null);
        $result = $this->loadUsers($pageSize, $startAfter);
        $users = collect($result['users']);

        if ($result['lastDoc']) {
            session()->put("users_start_after_page_" . ($page + 1), $result['lastDoc']);
        }

        return new LengthAwarePaginator(
            $users,
            $this->countTotalUsers(),
            $pageSize,
            $page,
            ['path' => request()->url(), 'pageName' => 'page']
        );
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
  
}