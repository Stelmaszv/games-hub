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
    public ?string $id = null;

    /**
    * @Assert\NotBlank
    */
    public ?string $createdBy = null;

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

    public ?bool $verified = false;

    public function __construct(array $editors = [])
    {
        $this->creationDate = new DateTime();
        $this->generalInformation = new GeneralInformationDTO();

        $this->editors = $editors;

        foreach ($editors as $key => $editor) {
            $this->editors[$key] = new EditorsDTO();
            $this->editors[$key]->uid = $editor['uid'];
        }
    }

    private ManagerRegistry $managerRegistry;

    public function setComponnets(array $componnets): void
    {
        $this->managerRegistry = $componnets['managerRegistry'];
    }

     /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        $user = $this->managerRegistry->getRepository(Publisher::class)->findOneBy(['createdBy' => $this->createdBy]);
        
        if($user !== null){
            $context->buildViolation('A user can only add one publisher.')
            ->atPath('createdBy')
            ->addViolation();   
        }

    }
}
