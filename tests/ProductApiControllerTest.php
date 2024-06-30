<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductApiControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private string $token;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->obtainAuthToken();
    }

    private function obtainAuthToken(): void
    {
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            [],
            json_encode(['username' => 'patrick.oryono@gmail.com', 'password' => 'football'])
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to obtain authentication token.');
        }

        $this->token = json_decode($content, true)['token'];
    }

    public function testFailsWithoutAuthentication()
    {
        $this->client->request('GET', '/api/products');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetProducts()
    {
        $this->client->request(
            'GET',
            '/api/products',
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer {$this->token}",
            ]
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetProduct()
    {
        $productId = $this->createProduct();
        $this->client->request(
            'GET',
            "/api/products/$productId",
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer {$this->token}",
            ]
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testCreateProduct()
    {
        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer {$this->token}",
            ],
            '{
                "name": "Product 11",
                "description": "some",
                "price": 10,
                "expiry_date": "2024-10-31",
                "date_of_manufacture": "2024-04-30"
            }'
        );

        $response = $this->client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Product 11', $responseData['name']);
        $this->assertArrayHasKey('id', $responseData);
    }

    /**
     * @depends testCreateProduct
     */
    public function testUpdateProduct()
    {
        $productId = $this->createProduct();
        $this->client->request(
            'PUT',
            "/api/products/$productId/edit",
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer {$this->token}",
            ],
            '{
                "name": "Updated Product 11"
            }'
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Updated Product 11', $responseData['name']);
    }

    /**
     * @depends testCreateProduct
     */
    public function testDeleteProduct()
    {
        $productId = $this->createProduct();
        $this->client->request(
            'DELETE',
            "/api/products/$productId",
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer {$this->token}",
            ]
        );

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

    public function testStoreProductWithInvalidName()
    {
        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer {$this->token}",
            ],
            '{
                "name": ""
            }'
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('name', $responseData['errors']);
        $this->assertEquals('This value should not be blank.', $responseData['errors']['name'][0]);
    }

    private function createProduct()
    {
        // Create the product
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            [],
            json_encode(['username' => 'patrick.oryono@gmail.com', 'password' => 'football'])
        );

        $response = $this->client->getResponse();

        $content = $response->getContent();

        $token = json_decode($content, true)['token'];

        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer {$token}",
            ],
            '{
                    "name": "product 11",
                    "description": "some",
                    "price": 10,
                    "expiry_date": "2024-10-31",
                    "date_of_manufacture": "2024-04-30"
                }'
        );

        dump($this->client->getRequest());

        return json_decode($this->client->getResponse()->getContent(), true)['id'];
    }
}
