<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClasseRepository::class)]
#[ApiResource()]
class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomclasse = null;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Garder::class,cascade:['remove','persist'])]
    private Collection $garders;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Inscription::class,cascade:['remove','persist'])]
    private Collection $inscriptions;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Emploi::class,cascade:['remove','persist'])]
    private Collection $emplois;   

    public function __toString()
    {
        return $this->nomclasse;
    } 

    public function __construct()
    {
        $this->garders = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
        $this->emplois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomclasse(): ?string
    {
        return $this->nomclasse;
    }

    public function setNomclasse(string $nomclasse): self
    {
        $this->nomclasse = $nomclasse;

        return $this;
    }

    /**
     * @return Collection<int, Garder>
     */
    public function getGarders(): Collection
    {
        return $this->garders;
    }

    public function addGarder(Garder $garder): self
    {
        if (!$this->garders->contains($garder)) {
            $this->garders->add($garder);
            $garder->setClasse($this);
        }

        return $this;
    }

    public function removeGarder(Garder $garder): self
    {
        if ($this->garders->removeElement($garder)) {
            // set the owning side to null (unless already changed)
            if ($garder->getClasse() === $this) {
                $garder->setClasse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setClasse($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getClasse() === $this) {
                $inscription->setClasse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Emploi>
     */
    public function getEmplois(): Collection
    {
        return $this->emplois;
    }

    public function addEmploi(Emploi $emploi): self
    {
        if (!$this->emplois->contains($emploi)) {
            $this->emplois->add($emploi);
            $emploi->setClasse($this);
        }

        return $this;
    }

    public function removeEmploi(Emploi $emploi): self
    {
        if ($this->emplois->removeElement($emploi)) {
            // set the owning side to null (unless already changed)
            if ($emploi->getClasse() === $this) {
                $emploi->setClasse(null);
            }
        }

        return $this;
    }
}
