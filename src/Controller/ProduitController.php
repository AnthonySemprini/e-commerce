<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Produit;

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

    #[Route('/show/{slug}', name: 'app_show_produit')]
    public function show(ProduitRepository $produitRepository, ?string $slug = null): Response
    {
        if ($slug === null) {
            
            return $this->redirectToRoute('app_home'); 
        }

        $produit = $produitRepository->findOneBy(['slug' => $slug]);

        if (!$produit) {
            throw $this->createNotFoundException('Le produit demandé n\'existe pas.');
        }

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    
    }
}

