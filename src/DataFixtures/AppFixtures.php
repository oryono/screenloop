<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Seeding 10 products

        $productsData = [
            ['name' => 'Product 1', 'description' => 'Description of Product 1', 'price' => 20.50],
            ['name' => 'Product 2', 'description' => 'Description of Product 2', 'price' => 15.75],
            ['name' => 'Product 3', 'description' => 'Description of Product 3', 'price' => 30.00],
            ['name' => 'Product 4', 'description' => 'Description of Product 4', 'price' => 25.99],
            ['name' => 'Product 5', 'description' => 'Description of Product 5', 'price' => 18.25],
            ['name' => 'Product 6', 'description' => 'Description of Product 6', 'price' => 22.49],
            ['name' => 'Product 7', 'description' => 'Description of Product 7', 'price' => 27.75],
            ['name' => 'Product 8', 'description' => 'Description of Product 8', 'price' => 33.99],
            ['name' => 'Product 9', 'description' => 'Description of Product 9', 'price' => 19.95],
            ['name' => 'Product 10', 'description' => 'Description of Product 10', 'price' => 16.50],
        ];

        foreach ($productsData as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setDateOfManufacture(new \DateTime('2023-01-01'));
            $product->setExpiryDate(new \DateTime('2024-12-31'));
            $product->setPrice($data['price']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
