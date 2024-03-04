<?php

namespace App\Entity;

use App\Repository\CommandeProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeProduitRepository::class)]
class CommandeProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $qtt = null;

    #[ORM\ManyToOne(inversedBy: 'commandeproduits')]
    private ?Commande $commande = null;

    #[ORM\ManyToOne(inversedBy: 'commandeproduits')]
    private ?Produit $produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQtt(): ?int
    {
        return $this->qtt;
    }

    public function setQtt(int $qtt): static
    {
        $this->qtt = $qtt;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}
