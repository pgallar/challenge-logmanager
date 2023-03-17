<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\AccountService;
use App\Services\MeliService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $accountService;
    protected $meliService;

    public function __construct(AccountService $accountService, MeliService $meliService)
    {
        $this->accountService = $accountService;
        $this->meliService = $meliService;
    }

    public function get()
    {
        return $this->accountService->getAllAccounts();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'client_id' => 'required|unique:accounts|max:255',
            'client_secret' => 'required|unique:accounts|max:255',
            'short_name' => 'required|unique:accounts|max:255'
        ]);

        $account = Account::create($validatedData);

        return response()->json([
            'message' => 'Account created successfully',
            'data' => $account,
        ], 201);
    }

    public function handleMercadoLibreCallback($shortName, Request $request)
    {
        $code = $request->input('code');
        $this->meliService->activate($shortName, $code);
        return redirect(env('FRONTEND_URL'));
    }
}
