<?php

declare(strict_types=1);

namespace App\Controller\Common;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DynamicValidationController extends AbstractController
{
    #[Route('/api/dynamic-validation/login/{email}', name: 'validation-login', methods: ['GET'])]
    public function validationLogin(ManagerRegistry $doctrine,string $email): JsonResponse
    {
        $user = $doctrine?->getRepository(User::class)?->findOneBy(['email' => $email]);

        return new JsonResponse([
            'user' => $email,
            'available' => ($user) ? false : true,
        ]);

    }
}
