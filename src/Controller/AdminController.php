<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findAll();
        
        return $this->render('admin/dashboard.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/product/new', name: 'admin_product_new')]
    public function newProduct(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product created successfully!');
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/product_form.html.twig', [
            'form' => $form->createView(),
            'product' => $product
        ]);
    }

    #[Route('/product/{id}/edit', name: 'admin_product_edit')]
    public function editProduct(Request $request, Product $product, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Product updated successfully!');
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/product_form.html.twig', [
            'form' => $form->createView(),
            'product' => $product
        ]);
    }

    #[Route('/product/{id}/delete', name: 'admin_product_delete', methods: ['POST'])]
    public function deleteProduct(Product $product, EntityManagerInterface $em): Response
    {
        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'Product deleted successfully!');
        return $this->redirectToRoute('admin_dashboard');
    }
}