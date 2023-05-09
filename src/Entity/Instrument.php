<?php

namespace App\Entity;

use App\Core\Entity\FileToModule;
use App\Repository\InstrumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity(repositoryClass: InstrumentRepository::class)]
class Instrument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 20)]
    private string $isin;

    #[ORM\Column(length: 50)]
    private string $investmentRegion;

    #[ORM\Column(length: 50)]
    private string $investmentSubject;

    #[ORM\Column(length: 250)]
    private string $fullName;

    #[ORM\Column(length: 50)]
    private string $managedBy;

    #[ORM\Column(length: 50)]
    private string $replicationType;

    #[OneToMany(mappedBy: 'instrument', targetEntity: InstrumentExchange::class)]
    private Collection $instrumentExchange;


    public function __construct()
    {
        $this->instrumentExchange = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIsin(): ?string
    {
        return $this->isin;
    }

    public function setIsin(string $isin): self
    {
        $this->isin = $isin;

        return $this;
    }

    public function getInvestmentRegion(): ?string
    {
        return $this->investmentRegion;
    }

    public function setInvestmentRegion(string $investmentRegion): self
    {
        $this->investmentRegion = $investmentRegion;

        return $this;
    }

    public function getInvestmentSubject(): ?string
    {
        return $this->investmentSubject;
    }

    public function setInvestmentSubject(string $investmentSubject): self
    {
        $this->investmentSubject = $investmentSubject;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getManagedBy(): ?string
    {
        return $this->managedBy;
    }

    public function setManagedBy(string $managedBy): self
    {
        $this->managedBy = $managedBy;

        return $this;
    }

    public function getReplicationType(): ?string
    {
        return $this->replicationType;
    }

    public function setReplicationType(string $replicationType): self
    {
        $this->replicationType = $replicationType;

        return $this;
    }

    /**
     * @return Collection<int, InstrumentExchange>
     */
    public function getInstrumentExchange(): Collection
    {
        return $this->instrumentExchange;
    }

    public function addInstrumentExchange(InstrumentExchange $instrumentExchange): self
    {
        if (!$this->instrumentExchange->contains($instrumentExchange)) {
            $this->instrumentExchange->add($instrumentExchange);
            $instrumentExchange->setInstrument($this);
        }

        return $this;
    }

    public function removeInstrumentExchange(InstrumentExchange $instrumentExchange): self
    {
        if ($this->instrumentExchange->removeElement($instrumentExchange)) {
            // set the owning side to null (unless already changed)
            if ($instrumentExchange->getInstrument() === $this) {
                $instrumentExchange->setInstrument(null);
            }
        }

        return $this;
    }
}
