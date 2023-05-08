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

    #[OneToMany(mappedBy: 'instrument', targetEntity: Quote::class)]
    private Collection $quotes;
    
    public function __construct()
    {
        $this->quotes = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Quote>
     */
    public function getQuotes(): Collection
    {
        return $this->quotes;
    }

    public function addQuote(Quote $quote): self
    {
        if (!$this->quotes->contains($quote)) {
            $this->quotes->add($quote);
            $quote->setInstrument($this);
        }

        return $this;
    }

    public function removeQuote(Quote $quote): self
    {
        if ($this->quotes->removeElement($quote)) {
            // set the owning side to null (unless already changed)
            if ($quote->getInstrument() === $this) {
                $quote->setInstrument(null);
            }
        }

        return $this;
    }
}
