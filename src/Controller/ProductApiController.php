<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/products')]
class ProductApiController extends AbstractController
{
    #[Route('', name: 'product_list', methods: ['GET'])]
    public function index(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();

        return $this->json($products);
    }


    #[Route('', name: 'product_store', methods: ['POST'])]
    public function store(ProductRepository $productRepository, Request $request)
    {
        $res = $productRepository->storeProduct(json_decode($request->getContent(), true));

        return $this->json($res);
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'], format: 'json')]
    public function show(ProductRepository $productRepository, $id)
    {
        $product = $productRepository->find($id);

        if (!$product) {
            $this->json(['message' => 'Product not found'], 404);
        }

        return $this->json($product);
    }

    #[Route('/{id}/edit', name: 'product_edit', methods: ['PUT'])]
    public function update($id)
    {
    }

    #[Route('/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, $id)
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json(['message' => 'Product not found'], 404);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json(['message' => 'Product deleted']);
    }
}
