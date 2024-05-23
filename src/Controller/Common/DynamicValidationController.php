<?php

declare(strict_types=1);

namespace App\Controller\Common;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\Validation\PasswordChecker;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DynamicValidationController extends AbstractController
{
    #[Route('/api/dynamic-validation/login/{email}', name: 'validation-login', methods: ['GET'])]
    public function validationLogin(ManagerRegistry $doctrine,string $email): JsonResponse
    {
        $user = $doctrine?->getRepository(User::class)?->findOneBy(['email' => $email]);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['message' => 'invalidEmail'], JsonResponse::HTTP_FORBIDDEN);
        }

        return new JsonResponse([
            'user' => $email,
            'available' => ($user) ? false : true,
        ]);

    }

    #[Route('/api/dynamic-validation/password-strength/{password}', name: 'password-strength', methods: ['GET'])]
    public function validationPasswordStrength(PasswordChecker $passwordChecker,string $password): JsonResponse
    {
        return new JsonResponse([
            'password' => $passwordChecker->checkPasswordStrength($password)
        ]);
    }
}
