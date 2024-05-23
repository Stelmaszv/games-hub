<?php

declare(strict_types=1);

namespace App\Validation\DTO\User;

use App\Entity\User;
use App\Generic\Api\Interfaces\DTO;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserDTO   implements DTO
{
    /**
     * @Assert\NotBlank(message="emptyField")
     * @Assert\Email(message="invalidEmail")
     */
    public $email;

    /**
     * @Assert\NotBlank(message="emptyField")
     * @Assert\Length(min=8,minMessage="fieldLenght lenght:8")
     */
    public $password;

    /**
     * @Assert\NotBlank(message="emptyField")
     * @Assert\Length(min=8,minMessage="fieldLenght lenght:8")
     */
    public $repartPassword;
    private ManagerRegistry $managerRegistry;

    public function __construct(string $email, string $password, string $repartPassword)
    {
        $this->email = $email;
        $this->password = $password;
        $this->repartPassword = $repartPassword;
    }

    /**
     * @Assert\Callback()
     */
    public function validatePasswords(ExecutionContextInterface $context, $payload)
    {
        if ($this->password !== $this->repartPassword) {
            $context->buildViolation('passwordsNotMatch')
                ->atPath('repartPassword')
                ->addViolation();
        }

        $user = $this->managerRegistry->getRepository(User::class)->findOneBy([ 'email' => $this->email ]);


        if ($user !== null) {
            $context->buildViolation('userExist')
                ->atPath('email')
                ->addViolation();
        }
    }

    public function setComponnetsData(array $componnets): void
    {
        $this->managerRegistry = $componnets['managerRegistry'];
    }
}
