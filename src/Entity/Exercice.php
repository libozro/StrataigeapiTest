<?php

namespace App\Entity;

use App\Repository\ExerciceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ExerciceRepository::class)
 */
class Exercice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("exercice:read")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups("exercice:read")
     */
    private $datedebut;

    /**
     * @ORM\Column(type="date")
     * @Groups("exercice:read")
     */
    private $datefin;

    /**
     * @ORM\Column(type="date")
     * @Groups("exercice:read")
     */
    private $anneecivil;

    /**
     * @ORM\OneToMany(targetEntity=Operation::class, mappedBy="exercice")
     */
    private $operation;

    public function __construct()
    {
        $this->operation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getAnneecivil(): ?\DateTimeInterface
    {
        return $this->anneecivil;
    }

    public function setAnneecivil(\DateTimeInterface $anneecivil): self
    {
        $this->anneecivil = $anneecivil;

        return $this;
    }

    /**
     * @return Collection<int, Operation>
     */
    public function getOperation(): Collection
    {
        return $this->operation;
    }

    public function addOperation(Operation $operation): self
    {
        if (!$this->operation->contains($operation)) {
            $this->operation[] = $operation;
            $operation->setExercice($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        if ($this->operation->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getExercice() === $this) {
                $operation->setExercice(null);
            }
        }

        return $this;
    }
}
