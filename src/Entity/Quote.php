<?php

namespace App\Entity;

use App\Repository\QuoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity(repositoryClass: QuoteRepository::class)]
#[UniqueConstraint(name: "instrument_date_unique", columns: ["instrument_id", "date"])]
class Quote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ManyToOne(targetEntity:Instrument::class, inversedBy:"quotes")]
    #[JoinColumn(name:"instrument_id", referencedColumnName:"id")]
    private Instrument|null $instrument = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $open = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $high = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $low = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $close = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $adj_close = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $volume = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getOpen(): ?string
    {
        return $this->open;
    }

    public function setOpen(?string $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getHigh(): ?string
    {
        return $this->high;
    }

    public function setHigh(?string $high): self
    {
        $this->high = $high;

        return $this;
    }

    public function getLow(): ?string
    {
        return $this->low;
    }

    public function setLow(?string $low): self
    {
        $this->low = $low;

        return $this;
    }

    public function getClose(): ?string
    {
        return $this->close;
    }

    public function setClose(?string $close): self
    {
        $this->close = $close;

        return $this;
    }

    public function getAdjClose(): ?string
    {
        return $this->adj_close;
    }

    public function setAdjClose(?string $adj_close): self
    {
        $this->adj_close = $adj_close;

        return $this;
    }

    public function getVolume(): ?string
    {
        return $this->volume;
    }

    public function setVolume(?string $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getInstrument(): ?Instrument
    {
        return $this->instrument;
    }

    public function setInstrument(?Instrument $instrument): self
    {
        $this->instrument = $instrument;

        return $this;
    }
}
