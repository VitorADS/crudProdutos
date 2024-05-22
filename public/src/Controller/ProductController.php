<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request): JsonResponse
    {
        $product = new Product();
        $productForm = $this->createForm(ProductType::class, $product);
        $productForm->submit(json_decode($request->getContent(), true));

        return $this->json([
            'success' => true,
            'product' => (string) $product
        ]);
    }
}
