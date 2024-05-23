<?php

declare(strict_types=1);

namespace App\Controller\Common;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class IsGrantedController extends AbstractController
{
    #[Route('/api/isGranted', name: 'isGranted', methods: ['POST'])]
    public function check(Security $security, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['attribute'])) {
            throw new \InvalidArgumentException('Attribute is required!');
        }

        $result = null;

        if (isset($data['subject'])) {
            $subject = $data['subject'];

            $table = 'App\Entity\\'.$subject['entity'];

            $query = $entityManager->createQuery(
                'SELECT p FROM '.$table.' p WHERE p.id = :entityId'
            )->setParameter('entityId', $subject['id']);

            $result = $query->getResult();

            if (empty($result)) {
                throw new \RuntimeException('Entity not found!');
            }

            $result = $result[0];
        }

        $isGranted = $security->isGranted($data['attribute'], $result);

        return new JsonResponse($isGranted);
    }
}
