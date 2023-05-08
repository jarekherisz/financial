<?php

namespace App\Entity;

use App\Repository\InstrumentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstrumentRepository::class)]
class Instrument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $symbol = null;

    #[ORM\Column(length: 50)]
    private ?string $exchange = null;

    #[ORM\Column(length: 50)]
    private ?string $yahoo_symbol = null;

    #[ORM\Column(length: 50)]
    private ?string $google_symbol = null;

    #[ORM\Column(length: 50)]
    private ?string $isin = null;

    #[ORM\Column(length: 100)]
    private ?string $dividend_module = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getYahooSymbol(): ?string
    {
        return $this->yahoo_symbol;
    }

    public function setYahooSymbol(string $yahoo_symbol): self
    {
        $this->yahoo_symbol = $yahoo_symbol;

        return $this;
    }

    public function getGoogleSymbol(): ?string
    {
        return $this->google_symbol;
    }

    public function setGoogleSymbol(string $google_symbol): self
    {
        $this->google_symbol = $google_symbol;

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

    public function getDividendModule(): ?string
    {
        return $this->dividend_module;
    }

    public function setDividendModule(string $dividend_module): self
    {
        $this->dividend_module = $dividend_module;

        return $this;
    }

    public function getExchange(): ?string
    {
        return $this->exchange;
    }

    public function setExchange(string $exchange): self
    {
        $this->exchange = $exchange;

        return $this;
    }
}
