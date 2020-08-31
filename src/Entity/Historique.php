<?php

namespace App\Entity;

use App\Repository\HistoriqueRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoriqueRepository::class)
 */
class Historique
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Formule::class, inversedBy="historiques")
     */
    private $formule;

    /**
     * @ORM\Column(type="bigint")
     */
    private $montant_gagne;

    public function __construct()
    {
        $this->date= new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getFormule(): ?Formule
    {
        return $this->formule;
    }

    public function setFormule(?Formule $formule): self
    {
        $this->formule = $formule;

        return $this;
    }

    public function getMontantGagne(): ?string
    {
        return $this->montant_gagne;
    }

    public function setMontantGagne(string $montant_gagne): self
    {
        $this->montant_gagne = $montant_gagne;

        return $this;
    }
}
