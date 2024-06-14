<?php

namespace App\Generic\Api\Trait;

use Symfony\Component\HttpFoundation\JsonResponse;

trait GenericJSONResponse
{
    private int|string|null $insertId = null;

    /**
     * @return array<mixed>
     */
    protected function onSuccessResponseMessage(): array
    {
        return [];
    }

    private function respondWithError(string $message, int $statusCode): JsonResponse
    {
        return new JsonResponse(['errors' => ['message' => $message]], $statusCode);
    }

    private function respondWithSuccess(int $statusCode): JsonResponse
    {
        $responseData = ['success' => true, 'id' => $this->insertId];
        $responseData = array_merge($responseData, $this->onSuccessResponseMessage());

        return new JsonResponse($responseData, $statusCode);
    }
}
