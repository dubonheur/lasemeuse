<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AbsenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbsenceRepository::class)]
#[ApiResource()]
class Absence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateab = null;

    #[ORM\Column(length: 255)]
    private ?string $motif = null;

    #[ORM\ManyToOne(inversedBy: 'absences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Inscription $numInsc = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateab(): ?\DateTimeInterface
    {
        return $this->dateab;
    }

    public function setDateab(\DateTimeInterface $dateab): self
    {
        $this->dateab = $dateab;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getNumInsc(): ?Inscription
    {
        return $this->numInsc;
    }

    public function setNumInsc(?Inscription $numInsc): self
    {
        $this->numInsc = $numInsc;

        return $this;
    }
}
