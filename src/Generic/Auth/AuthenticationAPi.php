<?php

namespace App\Generic\Auth;

use App\Entity\User;
use App\Roles\RoleUser;
use Symfony\Flex\Response;
use Symfony\Component\Uid\Uuid;
use App\Generic\Api\Interfaces\DTO;
use App\Validation\DTO\User\UserDTO;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Generic\Api\Identifier\Interfaces\IdentifierUid;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

trait AuthenticationAPi
{
    private JWT $security;

    public function __construct(JWT $jwt,ManagerRegistry $doctrine,ValidatorInterface $validator)
    {
        $this->jwt = $jwt;
        $this->managerRegistry = $doctrine;
        $this->validator = $validator;
    }

    #[Route('/api/logout', name: 'app_logout')]
    public function logout(): Response
    {
        throw new \LogicException('This route should not be called directly.');
    }

    #[Route('/api/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['message' => 'invalidDataLogin'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->managerRegistry?->getRepository(User::class)?->findOneBy(['email' => $data['email']]);

        if (!$user) {
            return new JsonResponse(['message' => 'invalidDataLogin'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!password_verify($data['password'], $user->getPassword())) {
            return new JsonResponse(['message' => 'invalidLoginOrPassword'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'token' => $this->jwt->encode($this->generateToken($user)),
        ]);
    }

    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(
            Request $request,  
            UserPasswordHasherInterface $userPasswordHasher,
        ): JsonResponse
    {
        $this->request = $request;

        $data = json_decode($request->getContent(), true);
        $issetData = isset($data['email']) && isset($data['password']) && isset($data['repartPassword']);


        if (!$issetData) {
            return new JsonResponse(['message' => 'No data provided'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $this->actionJsonData = null;

        $this->validationDTO(
            new UserDTO(
                $data['email'],
                $data['password'],
                $data['repartPassword']
            )
        );

        if($this->actionJsonData === null){

            $authenticationEntity = new User();
            $IdentifierUid = $authenticationEntity instanceof IdentifierUid;

            $hashedPassword = $userPasswordHasher->hashPassword(
                $authenticationEntity,
                $data['password']
            );

            if ($IdentifierUid) {
                $authenticationEntity->setId(Uuid::v4());
            }

            $authenticationEntity->setEmail($data['email']);
            $authenticationEntity->setPassword($hashedPassword);
            $authenticationEntity->setRoles([RoleUser::NAME]);

            $entityManager = $this->getManager();
            $entityManager->persist($authenticationEntity);
            $entityManager->flush();

            return new JsonResponse([
                'token' => $this->jwt->encode($this->generateToken($authenticationEntity)),
            ]);

        }else{
            return $this->actionJsonData;
        }

    }

    //Dublicate CODE  !!! GenericPostController
    protected function validationDTO(DTO $DTO): void
    {
        $DTO = $this->setDTO($DTO);
        $violations = $this->validator->validate($DTO);

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $data = [];
                $data['path'] = $violation->getPropertyPath();
                $data['message'] = $violation->getMessage();

                $errors[] = $data;
            }

            $errorMessages = [];
            foreach ($errors as $violation) {
                $errorMessages[$violation['path']] = $violation['message'];
            }

            $this->actionJsonData = new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    private function DTOComponnetsData(): array
    {
        return [
            'managerRegistry' => $this->managerRegistry,
            'request' => $this->request,
        ];
    }

    private function setDTO(DTO $DTO)
    {
        $DTO->setComponnetsData($this->DTOComponnetsData());

        return $DTO;
    }

    private function isPasswordValid(UserInterface $user, string $password): bool
    {
        return password_verify($password, $user->getPassword());
    }

    private function generateToken(UserInterface $user): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ];
    }

    #[Route(path: 'api/refresh-tokken/{id}', name: 'refresh_tokken')]
    public function refreshTokken(int $id,ManagerRegistry $doctrine){
        $userEntity = $doctrine?->getRepository(User::class)?->findOneBy(['id' => $id]);
        $jwt = $this->jwt->getJWTFromHeader();

        return new JsonResponse([
            'token' => $this->jwt->refreshToken($jwt,$this->generateToken($userEntity)),
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        throw new \LogicException('Login Action');
    }
}
