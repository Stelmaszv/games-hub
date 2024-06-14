<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use App\Generic\Api\Interfaces\DTO;
use App\Validation\DTO\Developer\DeveloperItemDTO;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PublisherDTO implements DTO
{
    public ?int $id = null;

    public ?int $createdBy = null;

    public \DateTime $creationDate;

    /**
     * @var DeveloperItemDTO[]
     */
    public array $developer = [];

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

    /**
     * @param array{
     *     add?: bool,
     *     generalInformation?: array<string>,
     *     editors?: array<EditorDTO>,
     *     descriptions?: array<string>,
     *     verified?: bool,
     *     developer?: array<array<string>>
     * } $data
     */
    public function __construct(array $data = [])
    {
        $this->add = $data['add'] ?? false;
        $this->creationDate = new \DateTime();
        $this->generalInformation = new GeneralInformationDTO($data['generalInformation']);
        $this->editors = [];

        if (isset($data['editors'])) {
            foreach ($data['editors'] as $key => $editor) {
                $this->editors[$key] = new EditorDTO();
                $this->editors[$key]->id = $editor['id'];
            }
        }

        $this->descriptions = new DescriptionsDTO($data['descriptions']);
        $this->verified = $data['verified'] ?? false;
        $this->developer = [];

        if (isset($data['developer'])) {
            foreach ($data['developer'] as $key => $developerData) {
                $this->developer[$key] = new DeveloperItemDTO($developerData);
            }
        }
    }

    /**
     * @param mixed[] $components
     */
    public function setComponentsData(array $components): void
    {
        $this->createdBy = $components['userId'];
        $this->edit = $components['edit'];
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, ?string $payload): void
    {
        if (true === $this->edit) {
            return;
        }

        if (false === $this->add) {
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
