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
        // Create the product
        $product = $this->createProduct();

        $this->client->request(
            'GET',
            "/api/products/$product",
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
                    "name": "product 11",
                    "description": "some",
                    "price": 10,
                    "expiry_date": "2024-10-31",
                    "date_of_manufacture": "2024-04-30"
                }'
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('product 11', json_decode($this->client->getResponse()->getContent(), true)['name']);
    }

    public function testGetSingleProduct()
    {
        // Create the product
        $product = $this->createProduct();

        $this->client->request(
            'GET',
            "/api/products/$product",
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer {$this->token}",

            ]
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateProduct()
    {
        // Create the product
        $product = $this->createProduct();


        $this->client->request(
            'PUT',
            "/api/products/$product/edit",
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer {$this->token}",

            ],
            '{
    "name": "Product Eleven"
}'
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Product Eleven', json_decode($this->client->getResponse()->getContent(), true)['name']);
    }

    public function testDeleteProduct()
    {
        // Create the product
        $product = $this->createProduct();

        $this->client->request(
            'DELETE',
            "/api/products/$product",
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer {$this->token}",

            ],
            '{
    "name": "Product Eleven"
}'
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

        $content = $this->client->getResponse()->getContent();

        $data = json_decode($content, true);

        $this->assertArrayHasKey('errors', $data);
        $this->assertArrayHasKey('name', $data['errors']);
        $this->assertEquals('This value should not be blank.', $data['errors']['name'][0]);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
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
