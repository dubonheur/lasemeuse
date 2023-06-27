<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AnneeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnneeRepository::class)]
#[ApiResource()]
class Annee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $debutan = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $finan = null;

    #[ORM\OneToMany(mappedBy: 'annee', targetEntity: Garder::class,cascade:['remove','persist'])]
    private Collection $garders;

    #[ORM\OneToMany(mappedBy: 'annee', targetEntity: Inscription::class,cascade:['remove','persist'])]
    private Collection $inscriptions;

    #[ORM\OneToMany(mappedBy: 'annee', targetEntity: Emploi::class,cascade:['remove','persist'])]
    private Collection $emplois;

    
    public function __toString()
    {
        return date('d/m/Y');
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

    public function getDebutan(): ?\DateTimeInterface
    {
        return $this->debutan;
    }

    public function setDebutan(\DateTimeInterface $debutan): self
    {
        $this->debutan = $debutan;

        return $this;
    }

    public function getFinan(): ?\DateTimeInterface
    {
        return $this->finan;
    }

    public function setFinan(\DateTimeInterface $finan): self
    {
        $this->finan = $finan;

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
            $garder->setAnnee($this);
        }

        return $this;
    }

    public function removeGarder(Garder $garder): self
    {
        if ($this->garders->removeElement($garder)) {
            // set the owning side to null (unless already changed)
            if ($garder->getAnnee() === $this) {
                $garder->setAnnee(null);
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
            $inscription->setAnnee($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getAnnee() === $this) {
                $inscription->setAnnee(null);
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
            $emploi->setAnnee($this);
        }

        return $this;
    }

    public function removeEmploi(Emploi $emploi): self
    {
        if ($this->emplois->removeElement($emploi)) {
            // set the owning side to null (unless already changed)
            if ($emploi->getAnnee() === $this) {
                $emploi->setAnnee(null);
            }
        }

        return $this;
    }
}
