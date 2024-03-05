<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: commandeproduit::class, mappedBy: 'produit')]
    private Collection $commandeproduits;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Categorie $categorie = null;


    #[ORM\Column(length: 255)]
    private ?string $imgUrl = null;

    public function __construct()
    {
        $this->commandeproduits = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, commandeproduit>
     */
    public function getCommandeproduits(): Collection
    {
        return $this->commandeproduits;
    }

    public function addCommandeproduit(commandeproduit $commandeproduit): static
    {
        if (!$this->commandeproduits->contains($commandeproduit)) {
            $this->commandeproduits->add($commandeproduit);
            $commandeproduit->setProduit($this);
        }

        return $this;
    }

    public function removeCommandeproduit(commandeproduit $commandeproduit): static
    {
        if ($this->commandeproduits->removeElement($commandeproduit)) {
            // set the owning side to null (unless already changed)
            if ($commandeproduit->getProduit() === $this) {
                $commandeproduit->setProduit(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }



    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(string $imgUrl): static
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }
}
