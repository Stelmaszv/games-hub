<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use App\Entity\Publisher;
use App\Generic\Api\Interfaces\DTO;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PublisherDTO implements DTO
{
    public ?int $id = null;

    public ?int $createdBy = null;

    public \DateTime $creationDate;

    public $developer = [];

    /**
     * @Assert\Valid
     */
    public ?GeneralInformationDTO $generalInformation = null;

    /**
     * @var EditorDTO[]
     *
     * @Assert\Valid
     *
     * @Assert\NotNull
     *
     * @Assert\Valid()
     */
    public array $editors = [];

    /**
     * @Assert\NotNull
     */
    public ?DescriptionsDTO $descriptions = null;

    public bool $verified = false;

    public bool $edit = false;

    public bool $add = false;

    private ManagerRegistry $managerRegistry;

    public function __construct(array $data = [])
    {
        $this->add = $data['add'] ?? false;
        $this->creationDate = new \DateTime();
        $this->generalInformation = new GeneralInformationDTO($data['generalInformation']);

        $this->editors = $data['editors'] ?? [];
        $this->descriptions = new DescriptionsDTO($data['descriptions']);
        $this->verified = $data['verified'] ?? false;

        if (isset($data['editors'])) {
            foreach ($data['editors'] as $key => $editor) {
                $this->editors[$key] = new EditorDTO($editor['id']);
                $this->editors[$key]->id = $editor['id'];
            }
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
        if (true === $this->edit) {
            return; 
        }

        if (null === $this->managerRegistry) {
            throw new \RuntimeException('ManagerRegistry is not set.');
        }

        $user = $this->managerRegistry->getRepository(Publisher::class)->findOneBy(['createdBy' => $this->createdBy]);

        if ($this->add === false){
            $context
                ->buildViolation('Add is Off.')
                ->atPath('add')
                ->addViolation();
        }
    }

    public function __toString()
    {
        return get_class($this);
    }
}
