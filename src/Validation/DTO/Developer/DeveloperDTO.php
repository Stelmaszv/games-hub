<?php

declare(strict_types=1);

namespace App\Validation\DTO\Developer;

use App\Generic\Api\Interfaces\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class DeveloperDTO implements DTO
{
    public ?string $id = null;

    public ?int $createdBy = null;

    public \DateTime $creationDate;

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
     * @var PublisherDTO[]
     *
     * @Assert\Valid
     *
     * @Assert\NotNull
     *
     * @Assert\Valid()
     */
    public array $publishers = [];

    /**
     * @Assert\NotNull
     */
    public ?DescriptionsDTO $descriptions = null;

    public bool $verified = false;

    public bool $edit = false;

     /**
     * @param array{
     *     add?: bool,
     *     generalInformation?: array<string>,
     *     editors?: array<EditorDTO>,
     *     descriptions?: array<string>,
     *     verified?: bool,
     *     publishers?: array<PublisherDTO>
     * } $data
     */
    public function __construct(array $data = [])
    {
        $this->creationDate = new \DateTime();
        $this->generalInformation = new GeneralInformationDTO($data['generalInformation'] ?? []);

        $this->editors = $data['editors'] ?? [];
        $this->publishers = $data['publishers'] ?? [];
        $this->verified = $data['verified'] ?? false;
        $this->descriptions = new DescriptionsDTO($data['descriptions'] ?? []);

        if (isset($data['editors'])) {
            foreach ($data['editors'] as $key => $editor) {
                $this->editors[$key] = new EditorDTO();
                $this->editors[$key]->id = $editor['id'];
            }
        }

        if (isset($data['publishers'])) {
            foreach ($data['publishers'] as $key => $publisher) {
                $this->publishers[$key] = new PublisherDTO();
                $this->publishers[$key]->id = $publisher['id'];
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
}
