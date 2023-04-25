<?php

namespace App\Entity;

use App\Repository\ModulesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModulesRepository::class)]
class Modules
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title_modules = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleModules(): ?string
    {
        return $this->title_modules;
    }

    public function setTitleModules(string $title_modules): self
    {
        $this->title_modules = $title_modules;

        return $this;
    }
}
