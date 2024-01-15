<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{

    // ces classes on etait creer en utilisant CLI Symphony
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $volume_horaire = null;

    #[ORM\ManyToMany(targetEntity: Ecole::class, inversedBy: 'formations')]
    #[JoinTable(name: 'formation_ecole')]
    private Collection $ecole;


    public function __construct()
    {
        $this->ecole = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getVolumeHoraire(): ?int
    {
        return $this->volume_horaire;
    }

    public function setVolumeHoraire(int $volume_horaire): static
    {
        $this->volume_horaire = $volume_horaire;

        return $this;
    }

    /**
     * @return Collection<int, Ecole>
     */
    public function getEcole(): Collection
    {
        return $this->ecole;
    }

    public function addEcole(Ecole $ecole): self
    {
        if (!$this->ecole->contains($ecole)) {
            $this->ecole->add($ecole);
        }

        return $this;
    }

    public function removeEcole(Ecole $ecole): self
    {
        $this->ecole->removeElement($ecole);

        return $this;
    }
}
