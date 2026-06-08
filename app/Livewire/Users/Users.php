<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\WithoutUrlPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Factory;
use Google\Cloud\CloudCore\Timestamp;
use Google\Cloud\Firestore\FirestoreClient;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Users extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $newPassword = '';
    public $confirmPassword = '';
    public $message = '';
    public $messageType = '';

    public $perPage = 10;
    public $query = '';

    public $selectedCollection; // 🔥 FIXED

    protected $queryString = ['query'];

    // =========================
    // INIT
    // =========================
    public function mount()
    {
        $this->selectedCollection = auth()->user()->collection;
    }

    public function render()
    {
        return view('livewire.users.users');
    }

    // =========================
    // COLLECTION LOCK (NO SWITCH)
    // =========================
    public function updatedSelectedCollection()
    {
        $this->selectedCollection = auth()->user()->collection;
        $this->resetPage();
    }

    // =========================
    // ADD USER REDIRECT
    // =========================
    public function addUser()
    {
        return redirect()->route('adduser');
    }

    // =========================
    // DELETE USER (FIXED)
    // =========================
    public function deleteUser($uid)
    {
        $db = $this->firestore();
        $auth = $this->firebaseAuth();

        $collection = auth()->user()->collection;

        try {
            $db->collection($collection)
                ->document($uid)
                ->delete();

            $auth->deleteUser($uid);

            $this->resetPage();

            session()->flash('message', 'User deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    // =========================
    // SEARCH RESET
    // =========================
    public function updatingQuery()
    {
        $this->resetPage();
    }

    // =========================
    // PASSWORD RESET UI
    // =========================
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

            $auth->updateUser($uid, [
                'password' => $this->newPassword
            ]);

            $this->reset(['newPassword','confirmPassword']);

            $this->message = "Password changed successfully.";
            $this->messageType = "success";

            $this->dispatch('close-password-modal');

        } catch (\Exception $e) {
            $this->message = "Password change failed.";
            $this->messageType = "error";
        }
    }

    // =========================
    // FIRESTORE CLIENT
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
    // FIREBASE AUTH
    // =========================
    protected function firebaseAuth(): Auth
    {
        $credentials = json_decode(env('FIREBASE_CREDENTIALS'), true);

        $credentials['private_key'] = str_replace("\\n", "\n", $credentials['private_key']);

        return (new Factory)
            ->withServiceAccount($credentials)
            ->createAuth();
    }

    // =========================
    // FIRESTORE CLEAN CONVERTER
    // =========================
    protected function firestoreToArray(array $data): array
    {
        foreach ($data as $key => $value) {

            if (is_array($value)) {
                $data[$key] = $this->firestoreToArray($value);

            } elseif ($value instanceof Timestamp) {
                $data[$key] = $value->get()->format('M d, Y');

            } elseif (is_object($value) && method_exists($value, 'toDateTime')) {
                $data[$key] = $value->toDateTime()->format('M d, Y');

            } else {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    // =========================
    // LOAD USERS (LOCKED COLLECTION)
    // =========================
    public function loadUsers($pageSize = null, $startAfter = null)
    {
        $db = $this->firestore();
        $pageSize = $pageSize ?? $this->perPage;

        $collection = auth()->user()->collection; // 🔥 LOCKED

        $query = $db->collection($collection);

        if ($this->query) {
            $query = $query->where('Name', '>=', $this->query)
                           ->where('Name', '<=', $this->query . "\uf8ff");
        }

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

    // =========================
    // COUNT USERS
    // =========================
    protected function countTotalUsers()
    {
        $db = $this->firestore();

        $collection = auth()->user()->collection;

        $documents = $db->collection($collection)->documents();

        $count = 0;

        foreach ($documents as $doc) {
            if ($doc->exists()) $count++;
        }

        return $count;
    }

    // =========================
    // PAGINATED COLLECTION
    // =========================
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

    // =========================
    // CSV EXPORT
    // =========================
    public function exportCsv(): StreamedResponse
    {
        $db = $this->firestore();
        $collection = auth()->user()->collection;

        $filename = 'users_' . $collection . '_' . now()->format('Ymd_His') . '.csv';

        $result = $this->loadUsers($this->perPage * 50);
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

        }, $filename, [
            'Content-Type' => 'text/csv'
        ]);
    }
}