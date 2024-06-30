<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiLoginControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }
    public function testSuccessfulLogin()
    {
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            [],
            json_encode(['username' => 'patrick.oryono@gmail.com', 'password' => 'football'])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('token', $data);
    }

    public function testFailedLoginWrongCredentials()
    {
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            [],
            json_encode(['username' => 'patrick.oryono@gmail.com', 'password' => 'wrongpassword'])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Invalid login credentials.', $data['message']);
    }

    public function testFailedLoginEmptyCredentials()
    {
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            [],
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Invalid login credentials.', $data['message']);
    }
}
