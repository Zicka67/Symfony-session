<?php

namespace App\Entity;

use App\Repository\FormerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormerRepository::class)]
class Former
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    private ?string $lastName = null;

    #[ORM\OneToMany(mappedBy: 'former', targetEntity: Session::class)]
    private Collection $Session;

    public function __construct()
    {
        $this->Session = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSession(): Collection
    {
        return $this->Session;
    }

    public function addSession(Session $session): self
    {
        if (!$this->Session->contains($session)) {
            $this->Session->add($session);
            $session->setFormer($this);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->Session->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getFormer() === $this) {
                $session->setFormer(null);
            }
        }

        return $this;
    }
}
