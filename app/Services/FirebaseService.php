<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $factory;

    public function __construct()
    {
        $this->factory = (new Factory)
            ->withServiceAccount(storage_path('app/private/firebase-adminsdk.json'));
    }

    public function auth()
    {
        return $this->factory->createAuth();
    }

    public function firestore()
    {
        return $this->factory->createFirestore()->database();
    }
}