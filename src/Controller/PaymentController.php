<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment", name="payment")
     */
    public function index(Request $request)
    {

        \Stripe\Stripe::setApiKey('sk_test_51H1sWcLy7uMHC2RaylK0bOSgK842qsbW3guK8w0aMVlRYnsypDkiqGfcw0WcVRuX3oEWJFpsm9vcN3Fd1I96MqBa00S1LOaX0e');

        if ($request->isMethod('POST')) {
            $charge = \Stripe\Charge::create([
                'amount' => 500,
                'currency' => 'eur',
                'description' => 'dons pour amélioration du site',
                'source' => 'tok_mastercard',
            ]);
            $this->addFlash('success', 'Paiement effectué avec succes !');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('payment/index.html.twig', [ 
            'controller_name' => 'PaymentController',
        ]);
    }
}
