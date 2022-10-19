<?php

namespace App\Entity;

use App\Repository\SessionCoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionCoursRepository::class)]
class SessionCours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $period_day = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    private ?Sessions $sessions = null;

    #[ORM\ManyToOne(inversedBy: 'sessionCours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Module $cours = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeriodDay(): ?int
    {
        return $this->period_day;
    }

    public function setPeriodDay(int $period_day): self
    {
        $this->period_day = $period_day;

        return $this;
    }

    public function getSessions(): ?Sessions
    {
        return $this->sessions;
    }

    public function setSessions(?Sessions $sessions): self
    {
        $this->sessions = $sessions;

        return $this;
    }

    public function getCours(): ?Module
    {
        return $this->cours;
    }

    public function setCours(?Module $cours): self
    {
        $this->cours = $cours;

        return $this;
    }


    public function __toString()
    {
        return $this->period_day;
    }
}
