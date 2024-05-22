<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private ProductService $productService
    )
    {
    }

    #[Route('/api/product', name: 'app_product', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $products = $this->productService->findBy([]);

        return $this->json([
            'success' => true,
            'products' => $products
        ]);
    }

    #[Route('/api/product/create', name: 'app_product_create', methods: ['POST'])]
    public function create(): JsonResponse
    {
        $products = $this->productService->findBy([]);

        return $this->json([
            'success' => true,
            'products' => $products
        ]);
    }
}
