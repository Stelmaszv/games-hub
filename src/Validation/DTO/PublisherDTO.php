<?php

declare(strict_types=1);

namespace App\Validation\DTO;

use DateTime;
use App\Entity\User;
use App\Entity\Publisher;
use App\Generic\Api\Interfaces\DTO;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PublisherDTO implements DTO
{
    public ?string $id = null;

    /**
     * @Assert\NotNull
     */
    public ?string $createdBy = null;

    public DateTime $creationDate; 
    
    /**
     * @Assert\NotNull
     */
    public ?array $generalInformation = null;

    /**
     * @Assert\NotNull
     */
    public ?array $descriptions = null;

    /**
     * @Assert\NotNull
     */
    public ?array $editors = null;

    public ?bool $verified = false;

    public function __construct()
    {
        $this->creationDate = new DateTime();
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
