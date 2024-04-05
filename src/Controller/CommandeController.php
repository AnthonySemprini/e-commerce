<?php

namespace App\Controller;


use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Entity\CommandeProduit;
use Symfony\Component\Mime\Email;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{

    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    
    #[Route('/commande/confirmation', name: 'app_commande_confirmation')]
    public function confirmationCommande(EntityManagerInterface $entityManager,Request $request, SessionInterface $session,  ProduitRepository $produitRepository ): Response
    {
        //verif si user 
        $this->denyAccessUnLessGranted('ROLE_USER');
        //recup session panier
        $panier = $session->get('panier', []);
        // dd($panier);
        $user = $this->getUser();

        $now = new \DateTime();//cree nouvel ojet date
        $commande = new Commande();//cree nouvel instance commande

        $commande->setUser($user);//attribue le user a la commande
        $commande->setDateCommande($now);//attribue date a la commande

        $form = $this->createForm(CommandeType::class, $commande);//cree le form pour l'objet commandde
        $form->handleRequest($request);// initialise le form

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->managerRegistry->getManager();
            //persist et enregistre l'objet commande
            $entityManager->persist($commande);
            $entityManager->flush();

            foreach( $panier as $prod => $qtt) {
                $produit = $produitRepository->find($prod);
                $commandeProduit = new CommandeProduit();

                $commandeProduit->setProduit($produit);
                $commandeProduit->setQtt($qtt);
                $commandeProduit->setCommande($commande);
                 //persist et enregistre 
                $entityManager->persist($commandeProduit);
                $entityManager->flush();
            }

            //vide le panier
            $session->set('panier', []);

            return $this->redirectToRoute('app_commande_recap', ['id' => $commande->getId()]);
        }

           // Si le panier est vide, redirige l'utilisateur vers la page d'accueil.
           if ($panier === []) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('commande/index.html.twig',[
            'commandeForm' =>$form->createView(),
            'reference' => $commande->getId()
        ]);
    }

    #[Route('/commande/recap/{id}', name: 'app_commande_recap')]
    public function recap($id, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $commande = $entityManager->getRepository(Commande::class)->find($id);

        if(!$commande) {
            throw $this->createNotFoundException('commande introuvable');
        }

        $total = 0;

        foreach($commande->getCommandeProduits() as $panier){
            $total += $panier->getProduit()->getPrix() * $panier->getQtt();
        }

        // $this->sendOrderConfirmationEmail($mailer, $commande);

        return $this->render('commande/valid.html.twig', [
            'commande' => $commande, // données à passer au template
            'total' => $total
        ]);
    }

    // private function sendOrderConfirmationEmail(MailerInterface $mailer, Commande $commande): void
    // {
    //     $total = 0;
    //     foreach ($commande->getcommandeProduits() as $panier) {
    //         $total += $panier->getProduit()->getPrix() * $panier->getQtt();
    //     } 

    //     $email = (new Email())
    //         ->from('adminBarberShop@mail.com') // Remplacez par votre adresse email
    //         ->to($commande->getUser()->getEmail())
    //         ->addTo('adminE-commerce@mail.com')
    //         ->subject('Confirmation de votre commande')
    //         ->html($this->renderView(
    //             'commande/emailConfirmation.html.twig',[
    //                 'commande' => $commande,
    //                 'total' => $total
    //             ]
    //         ));


    //     $mailer->send($email);
    // }

    // #[Route('/commande/pdf/{id}', name: 'app_commande_pdf')]
    // public function generatePdf(int $id, EntityManagerInterface $entityManager): Response
    // {
    //     // Récupérer la commande
    //     $commande = $entityManager->getRepository(Commande::class)->find($id);
    
    //     // Gérer l'erreur si la commande n'existe pas
    //     if (!$commande) {
    //         throw $this->createNotFoundException('La commande n\'a pas été trouvée.');
    //     }
    
    //     // Calculer le total
    //     $total = 0;
    //     foreach ($commande->getCommandeProduits() as $produitCommande) {
    //         $total += $produitCommande->getProduit()->getPrix() * $produitCommande->getQtt();
    //     }
    
    //     // Générer le HTML du PDF
    //     $html = $this->renderView('commande/fiche_pdf.html.twig', [
    //         'commande' => $commande,
    //         'total' => $total,
    //     ]);
    
    //     // Configurer Dompdf
    //     $dompdf = new Dompdf();
    //     $dompdf->setPaper('A4');
    //     $dompdf->loadHtml($html);
    
    //     // Rendu et envoi du PDF
    //     $pdfOutput = $dompdf->output();
    //     $response = new Response($pdfOutput);
    //     $response->headers->set('Content-Type', 'application/pdf');
    //     $response->headers->set('Content-Disposition', 'attachment; filename="commande_' . $id . '.pdf"');
    
    //     return $response;
    // }
}