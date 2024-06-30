<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/products')]
class ProductApiController extends AbstractController
{
    #[Route('', name: 'product_list', methods: ['GET'])]
    public function index(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 5);

        $page = max(1, $page);
        $limit = max(1, min(50, $limit));
        $products = $productRepository->paginate($page, $limit);

        return $this->json($products);
    }


    #[Route('', name: 'product_store', methods: ['POST'])]
    public function store(ProductRepository $productRepository, Request $request)
    {
        $res = $productRepository->storeProduct(json_decode($request->getContent(), true));

        return $this->json($res, 201);
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'], format: 'json')]
    public function show(ProductRepository $productRepository, $id)
    {
        $product = $productRepository->find($id);

        if (is_null($product)) {
            return $this->json(['message' => 'Product not found'], 404);
        }

        return $this->json($product);
    }

    #[Route('/{id}/edit', name: 'product_edit', methods: ['PUT'])]
    public function update(Request $request, ProductRepository $productRepository, $id): JsonResponse
    {
        $product = $productRepository->find($id);

        if (is_null($product)) {
            return $this->json(['message' => 'Product not found'], 404);
        }
        $product = $productRepository->updateProduct($id, json_decode($request->getContent(), true));

        return $this->json($product);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(ProductRepository $productRepository, $id)
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->json(['message' => 'Product not found'], 404);
        }

        $productRepository->deleteProduct($id);

        return $this->json(null, 204);
    }
}
