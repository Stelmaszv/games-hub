<?php

namespace App\Generic\Auth;

use App\Entity\User;
use App\Generic\Api\Identifier\Interfaces\IdentifierId;
use App\Generic\Api\Interfaces\DTO;
use App\Roles\RoleUser;
use App\Service\Validation\PasswordChecker;
use App\Validation\DTO\User\UserDTO;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Flex\Response;

trait AuthenticationAPi
{
    private JWT $jwt;
    private PasswordChecker $passwordChecker;
    private ManagerRegistry $managerRegistry;
    private ValidatorInterface $validator;
    private ?JsonResponse $actionJsonData;
    private Request $request;

    public function __construct(JWT $jwt, ManagerRegistry $doctrine, ValidatorInterface $validator)
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

        $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $data['email']]);

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
        PasswordChecker $passwordChecker
    ): JsonResponse {
        $this->request = $request;
        $this->passwordChecker = $passwordChecker;

        $data = json_decode($request->getContent(), true);
        $issetData = isset($data['email']) && isset($data['password']) && isset($data['repeatPassword']);

        if (!$issetData) {
            return new JsonResponse(['message' => 'No data provided'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $this->actionJsonData = null;

        $this->validationDTO(
            new UserDTO(
                $data['email'],
                $data['password'],
                $data['repeatPassword']
            )
        );

        if (null === $this->actionJsonData) {
            $authenticationEntity = new User();

            $hashedPassword = $userPasswordHasher->hashPassword(
                $authenticationEntity,
                $data['password']
            );

            $authenticationEntity->setEmail($data['email']);
            $authenticationEntity->setPassword($hashedPassword);
            $authenticationEntity->setRoles([RoleUser::NAME]);

            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($authenticationEntity);
            $entityManager->flush();

            return new JsonResponse([
                'token' => $this->jwt->encode($this->generateToken($authenticationEntity)),
            ]);
        } else {
            return $this->actionJsonData;
        }
    }

    // Dublicate CODE  !!! GenericPostController
    protected function validationDTO(DTO $DTO): void
    {
        $DTO = $this->setDTO($DTO);
        $violations = $this->validator->validate($DTO);

        if (count($violations) > 0) {
            $errors = [];

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

    /**
     * @return array<mixed>
     */
    private function DTOComponentsData(): array
    {
        return [
            'managerRegistry' => $this->managerRegistry,
            'request' => $this->request,
            'passwordChecker' => $this->passwordChecker,
        ];
    }

    private function setDTO(DTO $DTO): DTO
    {
        $DTO->setComponentsData($this->DTOComponentsData());

        return $DTO;
    }

    private function isPasswordValid(IdentifierId $user, string $password): bool
    {
        return password_verify($password, $user->getPassword());
    }

    /**
     * @return array<mixed>
     */
    private function generateToken(IdentifierId $user): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ];
    }

    #[Route(path: 'api/refresh-token/{id}', name: 'refresh_token')]
    public function refreshToken(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $userEntity = $doctrine->getRepository(User::class)->findOneBy(['id' => $id]);
        $jwt = $this->jwt->getJWTFromHeader();

        return new JsonResponse([
            'token' => $this->jwt->refreshToken($jwt, $this->generateToken($userEntity)),
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function loginAction(AuthenticationUtils $authenticationUtils): RedirectResponse
    {
        return $this->redirectToRoute('home');
    }
}
