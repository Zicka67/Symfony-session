<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    private ?Session $Session = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    private ?Modules $Modules = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->Session;
    }

    public function setSession(?Session $Session): self
    {
        $this->Session = $Session;

        return $this;
    }

    public function getModules(): ?Modules
    {
        return $this->Modules;
    }

    public function setModules(?Modules $Modules): self
    {
        $this->Modules = $Modules;

        return $this;
    }
}
