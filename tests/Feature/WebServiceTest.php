<?php

namespace Tests\Feature;

use App\Models\WebService;
use Google\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

class WebServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }

    public function test_a_user_can_connect_to_a_web_service_and_token_is_stored()
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setScopes');
            $mock->shouldReceive('createAuthUrl')->andReturn('http://localhost');
        });

        $response = $this->getJson(route('web-service.connect', 'google-drive'))
            ->assertOk()
            ->json();
        $this->assertEquals('http://localhost', $response['url']);
        $this->assertNotNull($response['url']);
    }

    public function test_web_service_callback_will_store_token()
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchAccessTokenWithAuthCode')
                ->andReturn('fake-token');
        });

        $this->postJson(route('web-service.callback'), ['code' => 'dummyCode'])
            ->assertCreated();

        $this->assertDatabaseHas('web_services', [
            'user_id' => $this->user->id,
            'token' => '{"access_token":"fake-token"}'
        ]);

        // $this->assertNotNull($this->user->services->first()->token);
    }
}