<?php

namespace App\Entity;

use App\Repository\DetailsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DetailsRepository::class)
 */
class Details
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
     * @ORM\ManyToOne(targetEntity=CategoriesDetails::class, inversedBy="details")
     */
    private $categoriesDetails;

    /**
     * @ORM\OneToMany(targetEntity=PetitesDetails::class, mappedBy="details")
     */
    private $petites_details;

    public function __construct()
    {
        $this->petites_details = new ArrayCollection();
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

    public function getCategoriesDetails(): ?CategoriesDetails
    {
        return $this->categoriesDetails;
    }

    public function setCategoriesDetails(?CategoriesDetails $categoriesDetails): self
    {
        $this->categoriesDetails = $categoriesDetails;

        return $this;
    }

    /**
     * @return Collection<int, PetitesDetails>
     */
    public function getPetitesDetails(): Collection
    {
        return $this->petites_details;
    }

    public function addPetitesDetail(PetitesDetails $petitesDetail): self
    {
        if (!$this->petites_details->contains($petitesDetail)) {
            $this->petites_details[] = $petitesDetail;
            $petitesDetail->setDetails($this);
        }

        return $this;
    }

    public function removePetitesDetail(PetitesDetails $petitesDetail): self
    {
        if ($this->petites_details->removeElement($petitesDetail)) {
            // set the owning side to null (unless already changed)
            if ($petitesDetail->getDetails() === $this) {
                $petitesDetail->setDetails(null);
            }
        }

        return $this;
    }
}
