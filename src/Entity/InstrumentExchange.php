<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;


#[ORM\Entity(repositoryClass: ExchangeRepository::class)]
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
}