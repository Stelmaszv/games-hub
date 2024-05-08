<?php

namespace App\Entity;

use App\Entity\JsonMaper\Developer\DescriptionsMapper;
use App\Entity\JsonMaper\Developer\EditorsMapper;
use App\Entity\JsonMaper\Developer\GeneralInformationMapper;
use App\Generic\Api\Identifier\Interfaces\IdentifierId;
use App\Generic\Api\Identifier\Trait\IdentifierById;
use App\Generic\Api\Interfaces\ApiInterface;
use App\Generic\Api\Trait\EntityApiGeneric;
use App\Generic\Api\Trait\JsonMapValidator;
use App\Repository\DeveloperRepository;
use App\Validation\DTO\Developer\DescriptionsDTO;
use App\Validation\DTO\Developer\GeneralInformationDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeveloperRepository::class)]
class Developer implements ApiInterface, IdentifierId
{
    use EntityApiGeneric;
    use IdentifierById;
    use JsonMapValidator;

    #[ORM\Column]
    private array $generalInformation = [];

    #[ORM\Column]
    private array $descriptions = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column]
    private array $editors = [];

    #[ORM\Column]
    private ?bool $verified = null;

    #[ORM\ManyToMany(targetEntity: Publisher::class, inversedBy: 'developers')]
    private Collection $publisher;

    public function __construct()
    {
        $this->publisher = new ArrayCollection();
    }

    public function getGeneralInformation(): array
    {
        return $this->generalInformation;
    }

    public function setGeneralInformation(GeneralInformationDTO $generalInformation): static
    {
        $this->generalInformation = $this->jsonValidate(get_object_vars($generalInformation), GeneralInformationMapper::class);

        return $this;
    }

    public function getDescriptions(): array
    {
        return $this->descriptions;
    }

    public function setDescriptions(DescriptionsDTO $descriptions): static
    {
        $this->descriptions = $this->jsonValidate(get_object_vars($descriptions), DescriptionsMapper::class);

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

    public function getEditors(): array
    {
        return $this->editors;
    }

    public function setEditors(array $editors): static
    {
        $this->editors = $this->jsonValidate($editors, EditorsMapper::class);

        return $this;
    }

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): static
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * @return Collection<int, Publisher>
     */
    public function getPublisher(): array
    {
        return $this->setApiGroupMany(new Publisher(), $this->publisher);
    }

    public function addPublisher(Publisher $publisher): static
    {
        if (!$this->publisher->contains($publisher)) {
            $this->publisher->add($publisher);
        }

        return $this;
    }

    public function removePublisher(Publisher $publisher): static
    {
        $this->publisher->removeElement($publisher);

        return $this;
    }
}
