<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/detail{id}', name: 'app_detail_produit')]
    public function detailProduit(Produit $produit): Response
    {
        //redirige vers la vue du detail d'un produit
        return $this->render('produit/detail.html.twig',[
            'produit' => $produit,
        ]
    );
    }
}
