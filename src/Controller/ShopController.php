<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\CartItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findBy(['active' => true]);
        
        return $this->render('shop/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/product/{id}', name: 'product_detail')]
    public function productDetail(Product $product): Response
    {
        return $this->render('shop/product_detail.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/add-to-cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $productId = $request->request->get('product_id');
        $quantity = (int) $request->request->get('quantity', 1);
        
        $product = $em->getRepository(Product::class)->find($productId);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        $sessionId = $request->getSession()->getId();
        
        // Check if item already exists in cart
        $cartItem = $em->getRepository(CartItem::class)->findOneBy([
            'sessionId' => $sessionId,
            'product' => $product
        ]);

        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + $quantity);
        } else {
            $cartItem = new CartItem();
            $cartItem->setSessionId($sessionId)
                     ->setProduct($product)
                     ->setQuantity($quantity);
        }

        $em->persist($cartItem);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Product added to cart']);
    }

    #[Route('/cart', name: 'cart')]
    public function cart(Request $request, EntityManagerInterface $em): Response
    {
        $sessionId = $request->getSession()->getId();
        $cartItems = $em->getRepository(CartItem::class)->findBy(['sessionId' => $sessionId]);

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->getTotalPrice();
        }

        return $this->render('shop/cart.html.twig', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    #[Route('/remove-from-cart/{id}', name: 'remove_from_cart', methods: ['POST'])]
    public function removeFromCart(CartItem $cartItem, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($cartItem);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/update-cart-quantity', name: 'update_cart_quantity', methods: ['POST'])]
    public function updateCartQuantity(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $cartItemId = $request->request->get('cart_item_id');
        $quantity = (int) $request->request->get('quantity');

        $cartItem = $em->getRepository(CartItem::class)->find($cartItemId);
        if (!$cartItem) {
            return new JsonResponse(['error' => 'Cart item not found'], 404);
        }

        if ($quantity <= 0) {
            $em->remove($cartItem);
        } else {
            $cartItem->setQuantity($quantity);
            $em->persist($cartItem);
        }

        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/checkout', name: 'checkout')]
    public function checkout(Request $request, EntityManagerInterface $em): Response
    {
        $sessionId = $request->getSession()->getId();
        $cartItems = $em->getRepository(CartItem::class)->findBy(['sessionId' => $sessionId]);

        if (empty($cartItems)) {
            return $this->redirectToRoute('cart');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->getTotalPrice();
        }

        return $this->render('shop/checkout.html.twig', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    #[Route('/payment/process', name: 'process_payment', methods: ['POST'])]
    public function processPayment(Request $request, EntityManagerInterface $em): Response
    {
        $paymentMethod = $request->request->get('payment_method');
        $sessionId = $request->getSession()->getId();
        
        // Simulate payment processing
        $success = true; // In a real app, you'd integrate with payment providers
        
        if ($success) {
            // Clear cart after successful payment
            $cartItems = $em->getRepository(CartItem::class)->findBy(['sessionId' => $sessionId]);
            foreach ($cartItems as $item) {
                $em->remove($item);
            }
            $em->flush();

            $this->addFlash('success', 'Payment processed successfully! Your order has been placed.');
            return $this->redirectToRoute('payment_success');
        }

        $this->addFlash('error', 'Payment failed. Please try again.');
        return $this->redirectToRoute('checkout');
    }

    #[Route('/payment/success', name: 'payment_success')]
    public function paymentSuccess(): Response
    {
        return $this->render('shop/payment_success.html.twig');
    }

    #[Route('/cart-count', name: 'cart_count')]
    public function cartCount(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $sessionId = $request->getSession()->getId();
        $cartItems = $em->getRepository(CartItem::class)->findBy(['sessionId' => $sessionId]);
        
        $count = 0;
        foreach ($cartItems as $item) {
            $count += $item->getQuantity();
        }

        return new JsonResponse(['count' => $count]);
    }
}