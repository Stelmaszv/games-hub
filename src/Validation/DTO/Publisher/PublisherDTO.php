<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use DateTime;
use App\Entity\Publisher;
use App\Generic\Api\Interfaces\DTO;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PublisherDTO implements DTO
{
    public ?int $id = null;

    public ?int $createdBy = null;

    public DateTime $creationDate; 
    
    /**
     * @var GeneralInformationDTO
     * @Assert\Valid
     */
    public ?GeneralInformationDTO  $generalInformation = null;

    /**
     * @var EditorsDTO[]
     * @Assert\Valid
     * @Assert\NotNull
     * @Assert\Valid()
     *
     */
    public array $editors = [];

    /**
     * @var DescriptionsDTO
     * @Assert\NotNull
     */
    public ?DescriptionsDTO $descriptions = null;

    public bool $verified = false;

    public bool $edit = false;

    private ManagerRegistry $managerRegistry;

    public function __construct(array $data = [])
    {
        $this->creationDate = new DateTime();
        $this->generalInformation = new GeneralInformationDTO($data['generalInformation']);
 
        $this->editors = $data['editors'];
        $this->descriptions = new DescriptionsDTO($data['descriptions']);
        $this->verified = $data['verified'] ?? false;

        foreach ($data['editors'] as $key => $editor) {
            $this->editors[$key] = new EditorsDTO();
            $this->editors[$key]->uid = $editor['uid'];
        }


    }

    public function setComponnetsData(array $componnets): void
    {
        $this->managerRegistry = $componnets['managerRegistry'];
        $this->createdBy = $componnets['userId'];
        $this->edit = $componnets['edit'];
    }

     /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->edit === true) {
            return; // Jeśli edit jest ustawione na true, pomijamy walidację
        }
    
        if ($this->managerRegistry === null) {
            throw new \RuntimeException('ManagerRegistry is not set.');
        }
    
        // Sprawdzamy, czy użytkownik istnieje
        $user = $this->managerRegistry->getRepository(Publisher::class)->findOneBy(['createdBy' => $this->createdBy]);
    
        if ($user !== null) {
            $context
                ->buildViolation('A user can only add one publisher.')
                ->atPath('createdBy')
                ->addViolation();
        }
    }

    public function __toString(){
        return get_class($this);
    }
}
