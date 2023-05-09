<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use App\Repository\InstrumentExchangeRepository;


#[ORM\Entity(repositoryClass: InstrumentExchangeRepository::class)]
class InstrumentExchange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ManyToOne(targetEntity:Instrument::class, inversedBy:"quotes")]
    #[JoinColumn(name:"instrument_id", referencedColumnName:"id")]
    private Instrument|null $instrument = null;

    #[OneToMany(mappedBy: 'instrument', targetEntity: Quote::class)]
    private Collection $quotes;

    #[ORM\Column(length: 20)]
    private string $ticker;

    #[ORM\Column(length: 10)]
    private string $exchange;

    #[ORM\Column(length: 10)]
    private string $currency;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $tickerGoogle;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $tickerYacho;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $dividendImportModule;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $quoteImportModule;

    public function __construct()
    {
        $this->quotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicker(): ?string
    {
        return $this->ticker;
    }

    public function setTicker(string $ticker): self
    {
        $this->ticker = $ticker;

        return $this;
    }

    public function getTickerGoogle(): ?string
    {
        return $this->tickerGoogle;
    }

    public function setTickerGoogle(?string $tickerGoogle): self
    {
        $this->tickerGoogle = $tickerGoogle;

        return $this;
    }

    public function getTickerYacho(): ?string
    {
        return $this->tickerYacho;
    }

    public function setTickerYacho(?string $tickerYacho): self
    {
        $this->tickerYacho = $tickerYacho;

        return $this;
    }

    public function getDividendImportModule(): ?string
    {
        return $this->dividendImportModule;
    }

    public function setDividendImportModule(?string $dividendImportModule): self
    {
        $this->dividendImportModule = $dividendImportModule;

        return $this;
    }

    public function getQuoteImportModule(): ?string
    {
        return $this->quoteImportModule;
    }

    public function setQuoteImportModule(?string $quoteImportModule): self
    {
        $this->quoteImportModule = $quoteImportModule;

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

    public function getExchange(): ?string
    {
        return $this->exchange;
    }

    public function setExchange(string $exchange): self
    {
        $this->exchange = $exchange;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}