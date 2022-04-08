<?php

namespace App\Entity;

use App\Repository\EcritureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EcritureRepository::class)
 */
class Ecriture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("ecriture:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("ecriture:read")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("ecriture:read")
     */
    private $compte;

    /**
     * @ORM\Column(type="float")
     * @Groups("ecriture:read")
     */
    private $montant;

    /**
     * @ORM\Column(type="date")
     * @Groups("ecriture:read")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Operation::class, inversedBy="ecriture")
     */
    private $operation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCompte(): ?string
    {
        return $this->compte;
    }

    public function setCompte(string $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
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

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function setOperation(?Operation $operation): self
    {
        $this->operation = $operation;

        return $this;
    }
}
