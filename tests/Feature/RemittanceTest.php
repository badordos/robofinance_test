<?php

namespace Tests\Feature;

use App\Http\Controllers\RemittanceController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RemittanceTest extends TestCase
{

    use DatabaseMigrations;

    private $users;
    private $userRepo;

    /**
     * @return void
     */
    public function setup(): void
    {
        parent::setUp();

        $this->users = User::factory(10)->create();
    }

    /**
     * Пользователь может увидеть информацию о себе на главной странице прежде чем сделать перевод
     *
     * @return void
     */
    public function testUserCanSeeSelfInfoAtMainPage()
    {
        $user = $this->users->first();
        $this->actingAs($user)->get(route('home'))
            ->assertOk()
            ->assertSee($user->name)
            ->assertSee($user->balance);
    }

    /**
     * Пользователь не может записать новый перевод с неправильными данными
     */
    public function testUserDontCanStoreRemittanceRequestWithInvalidData()
    {
        $user = $this->users->first();
        $a = [
            'value' => 'required|numeric|min:1|max:9999999999',
            'payer_id' => 'required|numeric|min:1',
            'recipient_id' => 'required|numeric|min:1|different:payer_id',
            'do_at' => 'required|date|after:now',
        ];

        $invalidData = [
            [
                'value' => '-1',
                'payer_id' => 'sadasd',
                'recipient_id' => '$sjkhfjkahsf123',
                'do_at' => '123123',
            ],
            [
                'value' => '0',
                'payer_id' => 123 . 'abc',
                'do_at' => 'today',
            ],
            [
                'payer_id' => new User(),
                'recipient_id' => null,
            ],
            [
                'value' => '99999999999999999999',
                'payer_id' => 0,
                'recipient_id' => 0,
                'do_at' => Carbon::now()->format('Y-m-d\TH:i'),
            ]
        ];

        foreach ($invalidData as $dataRequest) {
            $response = $this->post(route('remittance.store'), $dataRequest);
            $response->assertSessionHasErrors([
                'value',
                'payer_id',
                'recipient_id',
                'do_at',
            ]);
        }

    }

    /**
     * Пользователь может создать новый перевод
     */
    public function testUserCanStoreNewRemittance()
    {
        $user = $this->users->first();
        $user2 = $this->users->last();

        $response = $this->post(route('remittance.store'), [
            'value' => '100',
            'payer_id' => $user->getId(),
            'recipient_id' => $user2->getId(),
            'do_at' => Carbon::now()->addHour()->format('Y-m-d\TH:i'),
        ]);

        $response
            ->assertRedirect(route('home'))
            ->assertSessionHas('message', RemittanceController::SUCCESS_STORE_MSG);
    }

    /**
     * Пользователь не может создать перевод, который превышает его баланс
     */
    public function testUserDontCanStoreRemittanceMoreThenBalance()
    {
        $user = $this->users->first();
        $user2 = $this->users->last();

        $response = $this->post(route('remittance.store'), [
            'value' => $user->balance + 100,
            'payer_id' => $user->getId(),
            'recipient_id' => $user2->getId(),
            'do_at' => Carbon::now()->addHour()->format('Y-m-d\TH:i'),
        ]);

        $response
            ->assertStatus(500);
    }
}
