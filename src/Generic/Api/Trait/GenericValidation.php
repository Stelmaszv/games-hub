<?php

namespace App\Generic\Api\Trait;

use App\Generic\Api\Interfaces\DTO;
use Symfony\Component\HttpFoundation\JsonResponse;

trait GenericValidation
{
    protected function beforeValidation(): void
    {
    }

    protected function afterValidation(): void
    {
    }

    private function deserializeDto(string $data): object
    {
        return $this->serializer->deserialize($data, $this->dto, 'json');
    }

    /**
     * @return array<mixed>
     */
    private function validateDto(DTO $dto): array
    {
        return iterator_to_array($this->validator->validate($dto));
    }

    /**
     * @param array<mixed> $errors
     */
    private function validationErrorResponse(array $errors): JsonResponse
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[$error->getPropertyPath()] = $error->getMessage();
        }

        return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
    }
}
