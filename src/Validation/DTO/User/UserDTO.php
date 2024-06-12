<?php

declare(strict_types=1);

namespace App\Validation\DTO\User;

use App\Entity\User;
use App\Generic\Api\Interfaces\DTO;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\Validation\PasswordChecker;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserDTO implements DTO
{
    /**
     * @Assert\NotBlank(message="emptyField")
     * @Assert\Email(message="invalidEmail")
     */
    public $email;

    /**
     * @Assert\NotBlank(message="emptyField")
     */
    public $password;

    /**
     * @Assert\NotBlank(message="emptyField")
     */
    public $repeatPassword;
    private ManagerRegistry $managerRegistry;
    private PasswordChecker $passwordChecker;

    public function __construct(string $email, string $password, string $repeatPassword)
    {
        $this->email = $email;
        $this->password = $password;
        $this->repeatPassword = $repeatPassword;
    }

    /**
     * @Assert\Callback()
     */
    public function validatePasswords(ExecutionContextInterface $context, $payload)
    {
        $passwordStrength = $this->passwordChecker->checkPasswordStrength($this->password);

        if($passwordStrength === "weak"){
            $context->buildViolation('passwordsIsWeak')
            ->atPath('repeatPassword')
            ->addViolation();
        }

        if ($this->password !== $this->repeatPassword) {
            $context->buildViolation('passwordsNotMatch')
                ->atPath('repeatPassword')
                ->addViolation();
        }

        $user = $this->managerRegistry->getRepository(User::class)->findOneBy([ 'email' => $this->email ]);


        if ($user !== null) {
            $context->buildViolation('userExist')
                ->atPath('email')
                ->addViolation();
        }
    }

    public function setComponentsData(array $components): void
    {
        $this->managerRegistry = $components['managerRegistry'];
        $this->passwordChecker = $components['passwordChecker'];
    }
}
