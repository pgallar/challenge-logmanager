<?php

namespace App\Services;

use App\Models\Account;

class AccountService
{
    public function getAllAccounts()
    {
        return Account::all();
    }

    public function deleteAccount($id)
    {
        Account::findOrFail($id)->delete();
    }
}
