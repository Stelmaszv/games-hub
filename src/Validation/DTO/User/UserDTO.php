<?php

declare(strict_types=1);

namespace App\Validation\DTO\User;

use App\Entity\User;
use App\Generic\Api\Interfaces\DTO;
use App\Service\Validation\PasswordChecker;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserDTO implements DTO
{
    /**
     * @Assert\NotBlank(message="emptyField")
     *
     * @Assert\Email(message="invalidEmail")
     */
    public string $email;

    /**
     * @Assert\NotBlank(message="emptyField")
     */
    public string $password;

    /**
     * @Assert\NotBlank(message="emptyField")
     */
    public string $repeatPassword;
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
    public function validatePasswords(ExecutionContextInterface $context, ?string $payload): void
    {
        $passwordStrength = $this->passwordChecker->checkPasswordStrength($this->password);

        if ('weak' === $passwordStrength) {
            $context->buildViolation('passwordsIsWeak')
            ->atPath('repeatPassword')
            ->addViolation();
        }

        if ($this->password !== $this->repeatPassword) {
            $context->buildViolation('passwordsNotMatch')
                ->atPath('repeatPassword')
                ->addViolation();
        }

        $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $this->email]);

        if (null !== $user) {
            $context->buildViolation('userExist')
                ->atPath('email')
                ->addViolation();
        }
    }

    /**
     * @param mixed[] $components an array of strings representing components data
     */
    public function setComponentsData(array $components): void
    {
        $this->managerRegistry = $components['managerRegistry'];
        $this->passwordChecker = $components['passwordChecker'];
    }
}
