<?php

declare(strict_types=1);

namespace App\Controller\Common;

use App\Generic\Api\Trait\Security as SecurityTrait;
use App\Generic\Auth\JWT;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class IsGrantedController extends AbstractController
{
    use SecurityTrait;
    protected mixed $voterSubject = null;

    #[Route('/api/isGranted', name: 'isGranted', methods: ['POST'])]
    public function check(ManagerRegistry $doctrine, Security $security, Request $request, EntityManagerInterface $entityManager, JWT $jwt): JsonResponse
    {
        $entity = null;
        $id = null;
        $this->setSecurity($security);
        $this->setManagerRegistry($doctrine);
        
        $data = json_decode($request->getContent(), true);

        if (!isset($data['attribute'])) {
            throw new \InvalidArgumentException('Attribute is required!');
        }

        $this->voterAttribute = $data['attribute'];

        if (isset($data['subject'])) {
            $entity = $data['subject']['entity'];

            if (isset($data['subject']['id'])) {
                $id = $data['subject']['id'];
            }
        }

        if (null !== $entity) {
            $className = 'App\Entity\Publisher';
            $this->voterSubject = $className;
        }

        if (null !== $id) {
            $this->setId($id);
        }

        return $this->setSecurityView('accessGranted', $jwt);
    }

    public function accessGranted() : JsonResponse
    {
        return new JsonResponse(['success' => $this->access, 'message' => 'Access Granted'], JsonResponse::HTTP_ACCEPTED);
    }
}
