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

    #[ORM\Column]
    private ?int $modules_id = null;
  
    #[ORM\Column]
    private ?int $session_id = null;

    public function getModulesId(): ?int
    {
        return $this->Modules ? $this->Modules->getId() : null;
    }

    public function setModulesId(int $modules_id): self
    {
        $this->modules_id = $modules_id;

        return $this;
    }

    public function getSessionId(): ?int
    {
        return $this->Session ? $this->Session->getId() : null;
    }

    public function setSessionId(int $session_id): self
    {
        $this->session_id = $session_id;

        return $this;
    }


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
