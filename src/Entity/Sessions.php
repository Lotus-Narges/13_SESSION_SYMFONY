<?php

namespace App\Entity;

use App\Repository\SessionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionsRepository::class)]
class Sessions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $starting_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $ending_date = null;

    #[ORM\Column]
    private ?int $max_period_day = null;

    #[ORM\Column]
    private ?int $reserved_places = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    private ?Formation $formation = null;

    #[ORM\ManyToMany(targetEntity: Intern::class, inversedBy: 'sessions')]
    private Collection $interns;

    #[ORM\OneToMany(mappedBy: 'sessions', targetEntity: SessionCours::class)]
    private Collection $cours;

    #[ORM\Column(length: 50)]
    private ?string $title_session = null;

    #[ORM\Column]
    private ?int $total_places = null;

    public function __construct()
    {
        $this->interns = new ArrayCollection();
        $this->cours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartingDate(): ?\DateTimeInterface
    {
        return $this->starting_date;
    }

    public function setStartingDate(\DateTimeInterface $starting_date): self
    {
        $this->starting_date = $starting_date;

        return $this;
    }

    public function getEndingDate(): ?\DateTimeInterface
    {
        return $this->ending_date;
    }

    public function setEndingDate(\DateTimeInterface $ending_date): self
    {
        $this->ending_date = $ending_date;

        return $this;
    }

    public function getMaxPeriodDay(): ?int
    {
        return $this->max_period_day;
    }

    public function setMaxPeriodDay(int $max_period_day): self
    {
        $this->max_period_day = $max_period_day;

        return $this;
    }

    public function getReservedPlaces(): ?int
    {
        return $this->reserved_places;
    }

    public function setReservedPlaces(int $reserved_places): self
    {
        $this->reserved_places = $reserved_places;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection<int, Intern>
     */
    public function getInterns(): Collection
    {
        return $this->interns;
    }

    public function addIntern(Intern $intern): self
    {
        if (!$this->interns->contains($intern)) {
            $this->interns->add($intern);
        }

        return $this;
    }

    public function removeIntern(Intern $intern): self
    {
        $this->interns->removeElement($intern);

        return $this;
    }

    /**
     * @return Collection<int, SessionCours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(SessionCours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setSessions($this);
        }

        return $this;
    }

    public function removeCour(SessionCours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getSessions() === $this) {
                $cour->setSessions(null);
            }
        }

        return $this;
    }

    public function getTitleSession(): ?string
    {
        return $this->title_session;
    }

    public function setTitleSession(string $title_session): self
    {
        $this->title_session = $title_session;

        return $this;
    }


    public function getTotalPlaces(): ?int
    {
        return $this->total_places;
    }

    public function setTotalPlaces(int $total_places): self
    {
        $this->total_places = $total_places;

        return $this;
    }


    public function getPeriod() {
        $interval = date_diff($this->starting_date, $this->ending_date);
        return $interval -> format('%a');
    }


    public function getFreePlaces(){
        $freePlaces = $this->total_places - $this->reserved_places;
        return $freePlaces;
    }


    public function __toString()
    {
        return $this->title_session;      
    }

}
