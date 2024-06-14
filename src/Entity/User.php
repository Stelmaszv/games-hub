<?php

namespace App\Entity;

use App\Generic\Api\Identifier\Interfaces\IdentifierId;
use App\Generic\Api\Identifier\Trait\IdentifierById;
use App\Generic\Api\Interfaces\ApiInterface;
use App\Generic\Api\Trait\EntityApiGeneric;
use App\Generic\Auth\AuthenticationEntity;
use App\Generic\Auth\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, IdentifierId, ApiInterface
{
    use AuthenticationEntity;
    use IdentifierById;
    use EntityApiGeneric;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Developer::class)]
    private Collection $developers;

    public function __construct()
    {
        $this->developers = new ArrayCollection();
    }

    /**
     * @return Collection<int, Developer>
     */
    public function getDevelopers(): Collection
    {
        return $this->developers;
    }

    public function addDeveloper(Developer $developer): static
    {
        if (!$this->developers->contains($developer)) {
            $this->developers->add($developer);
            $developer->setCreatedBy($this);
        }

        return $this;
    }

    public function removeDeveloper(Developer $developer): static
    {
        if ($this->developers->removeElement($developer)) {
            if ($developer->getCreatedBy() == $this) {
                $developer->setCreatedBy(null);
            }
        }

        return $this;
    }
}
