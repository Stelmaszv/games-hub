<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PublisherRepository;
use App\Generic\Api\Trait\EntityApiGeneric;
use App\Generic\Api\Trait\JsonMapValidator;
use Doctrine\Common\Collections\Collection;
use App\Generic\Api\Interfaces\ApiInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Validation\DTO\Publisher\DescriptionsDTO;
use App\Generic\Api\Identifier\Trait\IdentifierById;
use App\Generic\Api\Identifier\Interfaces\IdentifierId;
use App\Validation\DTO\Publisher\GeneralInformationDTO;
use App\Entity\JsonMaper\Publisher\PublisherEditorsMapper;
use App\Entity\JsonMaper\Publisher\PublisherDescriptionsMapper;
use App\Entity\JsonMaper\Publisher\PublisherGeneralInformationMapper;

#[ORM\Entity(repositoryClass: PublisherRepository::class)]
class Publisher implements ApiInterface,IdentifierId
{
    use EntityApiGeneric;
    use IdentifierById;
    use JsonMapValidator;
    
    #[ORM\Column]
    private array $generalInformation = [];

    #[ORM\Column]
    private array $descriptions = [];

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $createdBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column]
    private array $editors = [];

    #[ORM\Column]
    private ?bool $verified = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'yes')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getGeneralInformation(): array
    {
        return $this->generalInformation;
    }

    public function setGeneralInformation(GeneralInformationDTO $generalInformation): static
    {
        $this->generalInformation = $this->jsonValidate(get_object_vars($generalInformation),PublisherGeneralInformationMapper::class);

        return $this;
    }

    public function getDescriptions(): array
    {
        return $this->descriptions;
    }

    public function setDescriptions(DescriptionsDTO $descriptions): static
    {
        $this->descriptions = $this->jsonValidate(get_object_vars($descriptions),PublisherDescriptionsMapper::class);

        return $this;
    }

    public function getCreatedBy(): ?array
    {
        return $this->setApiGroup(new User,'createdBy');
    }

    public function setCreatedBy(User $createdBy): static
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

    public function getEditors(): array
    {
        return $this->editors;
    }

    public function setEditors(array $editors): static
    {
        $this->editors = $this->jsonValidate($editors,PublisherEditorsMapper::class);

        return $this;
    }

    public function isEditor(string $user): bool
    {
        foreach($this->getEditors() as $editor){
            if($editor['uid'] === $user){
                return true;
            }
        }
        return false;
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
     * @return Collection<int, User>
     */
    public function getUsers(): array
    {
        return $this->setApiGroupMany(new User,$this->users);
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

        return $this;
    }
}