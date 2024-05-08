<?php

declare(strict_types=1);

namespace App\Generic\Api\Controllers;

use App\Generic\Api\Interfaces\ApiInterface;

class GenericListRelationController extends GenericListController
{
    protected ?string $relationMethod = null;

    protected function beforeQuery(): void
    {
        if (!$this->relationMethod) {
            throw new \Exception('RelationMethod is not define in controller '.get_class($this).'!');
        }

        if (!$this->relationMethod) {
            throw new \Exception('EntityLiteration is not define in controller '.get_class($this).'!');
        }

        if (null === $this->request->attributes->get('id')) {
            throw new \Exception('GenericDeleteController is not define in controller '.get_class($this).'!');
        }

        $entity = $this->getEntity();

        if (!$entity) {
            throw new \Exception('Object not found '.get_class($this).'!');
        }

        if (!method_exists($entity, $this->relationMethod)) {
            throw new \Exception($this->relationMethod.' not exist in Object'.get_class($entity).'!');
        }
    }

    protected function onQuerySet(): mixed
    {
        $method = $this->relationMethod;

        return $this->getEntity()->$method();
    }

    private function getEntity(): ApiInterface
    {
        return $this->repository->find($this->attributes->get('id'));
    }
}
