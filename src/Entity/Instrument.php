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

    #[ORM\Column(length: 50)]
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
}
