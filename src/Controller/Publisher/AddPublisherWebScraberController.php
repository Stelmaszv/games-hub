<?php

declare(strict_types=1);

namespace App\Controller\Publisher;
use DateTime;
use App\Entity\User;
use App\Entity\Publisher;
use Symfony\Component\Uid\UuidV4;
use App\Generic\Api\Trait\GenericValidation;
use App\Validation\DTO\Publisher\EditorsDTO;
use App\Validation\DTO\Publisher\PublisherDTO;
use Symfony\Component\Routing\Annotation\Route;
use App\Validation\DTO\Publisher\DescriptionsDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Generic\Api\Controllers\GenericPostController;
use App\Service\WebScraber\Publisher\PublisherScraber;
use App\Validation\DTO\Publisher\GeneralInformationDTO;
use App\Validation\DTO\Publisher\PublisherWebScraberDTO;


#[Route("api/publisher/web-scraber/add/", name: "publisher_add", methods: ["POST"])]
class AddPublisherWebScraberController extends GenericPostController
{
    use GenericValidation;
    protected ?string $dto = PublisherWebScraberDTO::class;

    protected function action(): void
    {
        $publisherDTO = $this->setPublisherDTO();
        $this->validation($publisherDTO);

        if($this->actionJsonData === null){
            $this->savePublisher($publisherDTO);
        }
    }

    private function getTokken(){
        $authorizationHeader = $this->request->headers->get('Authorization');

        if (strpos($authorizationHeader, 'Bearer ') === 0) {
            return substr($authorizationHeader, 7);
        }
        

        return null;
    }

    private function setPublisherDTO() : PublisherDTO
    {
        $data = json_decode($this->request->getContent(), true);
        $publisherScraber = new PublisherScraber($data['url']);

        $publisherDTO = new PublisherDTO(
            $this->setGenaralInformation($publisherScraber->getGeneralInformation()),
            new DescriptionsDTO(),
            [],
        );

        $publisherDTO->setComponnetsData([
            'managerRegistry' => $this->managerRegistry,
            'request' => $this->request,
            'userId' => $this->jwt->decode($this->getTokken())['id'],
            'edit' => false
        ]);

        return $publisherDTO;
    }

    private function savePublisher(PublisherDTO $publisherDTO) :void 
    {
        $user = $this->managerRegistry->getRepository(User::class)->find($this->jwt->decode($this->getTokken())['id']);
        $uuidObject = new UuidV4();
        $uuidString = $uuidObject->toRfc4122();

        $publisher = new Publisher();
        $publisher->setId($uuidString);
        $publisher->setCreationDate(new DateTime());
        $publisher->setEditors($publisherDTO->editors);
        $publisher->setGeneralInformation($publisherDTO->generalInformation);
        $publisher->setDescriptions($publisherDTO->descriptions);
        $publisher->setVerified($publisherDTO->verified);
        $publisher->setCreatedBy($user);

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($publisher);
        $entityManager->flush();
    }

    private function setGenaralInformation(array $publisherGenaralInformation) : GeneralInformationDTO 
    {
        return new GeneralInformationDTO(
            $publisherGenaralInformation['name'] ?? null,
            $publisherGenaralInformation['founded'] ?? null,
            $publisherGenaralInformation['funder'] ?? null,
            $publisherGenaralInformation['headquarter'] ?? null,
            $publisherGenaralInformation['origin'] ?? null,
            $publisherGenaralInformation['website'] ?? null
        );
    }

    private function validation(PublisherDTO $publisherDTO) : void {

        $violations = $this->validator->validate($publisherDTO);

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
}
