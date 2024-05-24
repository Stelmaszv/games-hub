<?php

declare(strict_types=1);

namespace App\Controller\Common;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Generic\Api\Trait\Security as SecurityTrait;
use App\Generic\Auth\JWT;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;

class IsGrantedController extends AbstractController
{
    private Security $security;
    private int $id;
    private ManagerRegistry $managerRegistry;

    use SecurityTrait;
    #[Route('/api/isGranted', name: 'isGranted', methods: ['POST'])]
    public function check(ManagerRegistry $doctrine,Security $security,Request $request, EntityManagerInterface $entityManager,JWT $jwt): JsonResponse
    {
        $this->security = $security;
        $this->managerRegistry = $doctrine;

        $data = json_decode($request->getContent(), true);
        

        if (!isset($data['attribute'])) {
            throw new \InvalidArgumentException('Attribute is required!');
        }
        
        $this->voterAtribute = $data['attribute'];

        if(isset($data['entity'])){
            $className = 'App\Entity\\' . $data['entity'];
            $this->voterSubject = $className;
        }

        if(isset($data['id'])){
            $this->id = $data['id'];
        }

        return $this->setSecurityView('accessGranted',$jwt);
    }

    function accessGranted(){
        return new JsonResponse(['success' => $this->access, 'message' => 'Access Granted'], JsonResponse::HTTP_ACCEPTED);
    }
}
