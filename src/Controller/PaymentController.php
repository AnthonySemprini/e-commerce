<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Stripe\Stripe;

class PaymentController extends AbstractController
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/order/create-session-stripe/{reference}', name: 'payment_stripe')]
    public function stripeCheckout($reference): RedirectResponse
    {

        $commande = $this->em->getRepository(Commande::class)->findOneBy(['reference' => $reference]);
        dd($commande);

        Stripe::setApiKey(sk_test_51OtXk6AHMURQbTn4CK7nsagaha3hGBT0RARTLYAHGxD1V2HjZUxJANwSnOmsxrYo99ED7SLXFgR3iMqLH811KhvW00EH68s3Ey);

        // header(header: 'content-Type: application/Json');

        // $YOUR_DOMAIN = 'http://127.0.0.1:8002/';

        // $checkout_session = \Stripe\Checkout\Session::create([
        //     'line_items' => [
        //         [
        //             'price' => '{{PRICE_ID}}',
        //             'quantity' => 1,
        //         ]
        //     ],
        //     'mode' => 'payement',
        //     'sucess_url' => $YOUR_DOMAIN . '/success.html',
        //     'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        // ]);
    }
}