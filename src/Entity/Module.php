<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title_module = null;

    #[ORM\OneToMany(mappedBy: 'cours', targetEntity: SessionCours::class, orphanRemoval: true)]
    private Collection $sessionCours;

    #[ORM\ManyToOne(inversedBy: 'modules')]
    private ?Category $category = null;

    public function __construct()
    {
        $this->sessionCours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleModule(): ?string
    {
        return $this->title_module;
    }

    public function setTitleModule(string $title_module): self
    {
        $this->title_module = $title_module;

        return $this;
    }

    /**
     * @return Collection<int, SessionCours>
     */
    public function getSessionCours(): Collection
    {
        return $this->sessionCours;
    }

    public function addSessionCour(SessionCours $sessionCour): self
    {
        if (!$this->sessionCours->contains($sessionCour)) {
            $this->sessionCours->add($sessionCour);
            $sessionCour->setCours($this);
        }

        return $this;
    }

    public function removeSessionCour(SessionCours $sessionCour): self
    {
        if ($this->sessionCours->removeElement($sessionCour)) {
            // set the owning side to null (unless already changed)
            if ($sessionCour->getCours() === $this) {
                $sessionCour->setCours(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }


    public function __toString()
    {
        return $this->title_module;
    }
}
