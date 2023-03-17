<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\AccountController;
use App\Models\Account;
use App\Services\AccountService;
use App\Services\MeliService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    private $accountServiceMock;
    private $meliServiceMock;
    private $accountController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountServiceMock = $this->createMock(AccountService::class);
        $this->meliServiceMock = $this->createMock(MeliService::class);

        $this->accountController = new AccountController(
            $this->accountServiceMock,
            $this->meliServiceMock
        );
    }

    public function test_get_returns_all_accounts()
    {
        $accounts = Account::factory()->create();
        $this->accountServiceMock->expects($this->once())
            ->method('getAllAccounts')
            ->willReturn($accounts);

        $response = $this->accountController->get();

        $this->assertEquals($accounts->toArray(), $response->toArray());
    }

    public function test_handleMercadoLibreCallback_activates_meli_service_and_redirects_to_frontend()
    {
        $shortName = 'test';
        $code = '123456';
        $this->meliServiceMock->expects($this->once())
            ->method('activate')
            ->with($shortName, $code);

        $response = $this->accountController->handleMercadoLibreCallback(
            $shortName,
            new Request(['code' => $code])
        );

        $this->assertEquals(env('FRONTEND_URL'), $response->getTargetUrl());
    }
}
