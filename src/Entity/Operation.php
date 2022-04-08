<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OperationRepository::class)
 */
class Operation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("operation:read")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Ecriture::class, mappedBy="operation")
     */
    private $ecriture;

    /**
     * @ORM\ManyToOne(targetEntity=Exercice::class, inversedBy="operation")
     */
    private $exercice;

    public function __construct()
    {
        $this->ecriture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Ecriture>
     */
    public function getEcriture(): Collection
    {
        return $this->ecriture;
    }

    public function addEcriture(Ecriture $ecriture): self
    {
        if (!$this->ecriture->contains($ecriture)) {
            $this->ecriture[] = $ecriture;
            $ecriture->setOperation($this);
        }

        return $this;
    }

    public function removeEcriture(Ecriture $ecriture): self
    {
        if ($this->ecriture->removeElement($ecriture)) {
            // set the owning side to null (unless already changed)
            if ($ecriture->getOperation() === $this) {
                $ecriture->setOperation(null);
            }
        }

        return $this;
    }

    public function getExercice(): ?Exercice
    {
        return $this->exercice;
    }

    public function setExercice(?Exercice $exercice): self
    {
        $this->exercice = $exercice;

        return $this;
    }
}
