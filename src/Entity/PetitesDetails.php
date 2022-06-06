<?php

namespace App\Entity;

use App\Repository\PetitesDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PetitesDetailsRepository::class)
 */
class PetitesDetails
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
     * @ORM\ManyToOne(targetEntity=Details::class, inversedBy="petites_details")
     */
    private $details;

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

    public function getDetails(): ?Details
    {
        return $this->details;
    }

    public function setDetails(?Details $details): self
    {
        $this->details = $details;

        return $this;
    }
}
