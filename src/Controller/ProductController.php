<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\ProductService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductService $productService
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

    /**
     * @throws Exception
     */
    #[Route('/api/product/create', name: 'app_product_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $product = new Product();
        $productForm = $this->createForm(ProductType::class, $product);
        return $this->submitAndSave($productForm, $product, $request);
    }

    /**
     * @throws Exception
     */
    #[Route('/api/product/edit/{product}', name: 'app_product_edit', methods: ['PUT'])]
    public function edit(Request $request, Product $product): JsonResponse
    {
        $productForm = $this->createForm(ProductType::class, $product);
        return $this->submitAndSave($productForm, $product, $request, false);
    }

    #[Route('/api/product/remove/{product}', name: 'app_product_remove', methods: ['DELETE'])]
    public function remove(Request $request, Product $product): JsonResponse
    {
        try{
            $this->productService->remove($product);

            return $this->json([
                'success' => true,
                'message' => 'Registro removido com sucesso!'
            ], Response::HTTP_OK);
        }catch (Exception $e){
            return $this->json([
                'success' => false,
                'message' => 'Houve algum problema ao tentar remover o registro!'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @throws Exception
     */
    private function submitAndSave(FormInterface $productForm, Product $product, Request $request, bool $isCreate = true): JsonResponse
    {
        $productForm->submit(json_decode($request->getContent(), true));

        if($productForm->isValid()) {
            $product = $this->productService->save($product);

            return $this->json([
                'success' => true,
                'product' => $product
            ], $isCreate ? Response::HTTP_CREATED : Response::HTTP_OK);
        }

        $errorForm = $productForm->getErrors(true);

        if(count($errorForm) > 0){
            $error = (string) $errorForm;
        } else {
            $error = 'Nao foi possivel salvar o registro!';
        }

        return $this->json([
            'success' => false,
            'message' => $error
        ], Response::HTTP_BAD_REQUEST);
    }
}
