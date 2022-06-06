<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Annonces::class, mappedBy="categorie")
     */
    private $annonces;

    /**
     * @ORM\OneToMany(targetEntity=CategoriesDetails::class, mappedBy="categories")
     */
    private $categorie_details;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->categorie_details = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Annonces>
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonces $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setCategorie($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonces $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getCategorie() === $this) {
                $annonce->setCategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CategoriesDetails>
     */
    public function getCategorieDetails(): Collection
    {
        return $this->categorie_details;
    }

    public function addCategorieDetail(CategoriesDetails $categorieDetail): self
    {
        if (!$this->categorie_details->contains($categorieDetail)) {
            $this->categorie_details[] = $categorieDetail;
            $categorieDetail->setCategories($this);
        }

        return $this;
    }

    public function removeCategorieDetail(CategoriesDetails $categorieDetail): self
    {
        if ($this->categorie_details->removeElement($categorieDetail)) {
            // set the owning side to null (unless already changed)
            if ($categorieDetail->getCategories() === $this) {
                $categorieDetail->setCategories(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

}
