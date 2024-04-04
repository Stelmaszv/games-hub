<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\PublisherRepository;
use App\Generic\Api\Trait\EntityApiGeneric;
use App\Generic\Api\Trait\JsonMapValidator;
use App\Generic\Api\Interfaces\ApiInterface;
use App\Generic\Api\Identifier\Trait\IdentifierByUid;
use App\Generic\Api\Identifier\Interfaces\IdentifierUid;

#[ORM\Entity(repositoryClass: PublisherRepository::class)]
class Publisher implements ApiInterface,IdentifierUid
{
    use EntityApiGeneric;
    use IdentifierByUid;
    use JsonMapValidator;
    
    #[ORM\Column]
    private array $generalInformation = [];

    #[ORM\Column]
    private array $descriptions = [];

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $createdBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;


    public function getGeneralInformation(): array
    {
        return $this->generalInformation;
    }

    public function setGeneralInformation(array $generalInformation): static
    {
        $this->generalInformation = $generalInformation;

        return $this;
    }

    public function getDescriptions(): array
    {
        return $this->descriptions;
    }

    public function setDescriptions(array $descriptions): static
    {
        $this->descriptions = $descriptions;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }
}
