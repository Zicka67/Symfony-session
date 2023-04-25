<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title_formation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleFormation(): ?string
    {
        return $this->title_formation;
    }

    public function setTitleFormation(string $title_formation): self
    {
        $this->title_formation = $title_formation;

        return $this;
    }
}
