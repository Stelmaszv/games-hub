<?php

declare(strict_types=1);

namespace App\Validation\DTO\Developer;

use DateTime;
use App\Generic\Api\Interfaces\DTO;
use Doctrine\Persistence\ManagerRegistry;
use App\Validation\DTO\Publisher\EditorsDTO;
use App\Validation\DTO\Publisher\DescriptionsDTO;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validation\DTO\Publisher\GeneralInformationDTO;

class DeveloperDTO implements DTO
{
    public ?string $id = null;

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
     * @var PublisherDTO[]
     * @Assert\Valid
     * @Assert\NotNull
     * @Assert\Valid()
     *
     */
    public array $publishers = [];

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
 
        $this->editors = $data['editors'] ?? [];
        $this->publishers  = $data['publishers'] ?? [];
        $this->verified = $data['verified'] ?? false;
        $this->descriptions = new DescriptionsDTO($data['descriptions']);

        if (isset($data['editors'])) {
            foreach ($data['editors'] as $key => $editor) {
                $this->editors[$key] = new EditorsDTO();
                $this->editors[$key]->id = $editor['id'];
            }
        }

        if (isset($data['publishers'])) {
            foreach ($data['publishers'] as $key => $editor) {
                $this->publishers[$key] = new PublisherDTO();
                $this->publishers[$key]->id = $editor['id'];
            }
        }

    }

    public function setComponnetsData(array $componnets): void
    {
        $this->managerRegistry = $componnets['managerRegistry'];
        $this->createdBy = $componnets['userId'];
        $this->edit = $componnets['edit'];
    }
}
