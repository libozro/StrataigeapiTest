<?php

namespace App\Entity;

use App\Repository\DossierRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=DossierRepository::class)
 */
class Dossier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     */
    private $raisonsocial;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $rcccm;

    /**
     * @ORM\Column(type="date")
     */
    private $datedebut;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaisonsocial(): ?string
    {
        return $this->raisonsocial;
    }

    public function setRaisonsocial(string $raisonsocial): self
    {
        $this->raisonsocial = $raisonsocial;

        return $this;
    }

    public function getRcccm(): ?string
    {
        return $this->rcccm;
    }

    public function setRcccm(string $rcccm): self
    {
        $this->rcccm = $rcccm;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }
}
