<?php

namespace App\Generic\Web\Trait;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

trait GenericForm
{
    private FormFactoryInterface $formFactory;
    private Request $request;
    protected ?string $twig = null;
    protected ?string $form = null;
    protected ?Object $item;

    /**
     * @return array<string>
     */
    protected function onSetAttribute(): array
    {
        return [];
    }


    /**
     * @return array<string>
     */
    private function getAttributes(): array
    {
        $attributes['form'] = $this->setFormToAttribute();

        return array_merge($attributes, $this->onSetAttribute());
    }

    private function setFormToAttribute() : mixed
    {
        return $this->setForm($this->item)->createView();
    }

    private function setForm(object $entity): mixed
    {
        $form = $this->formFactory->create($this->form, $entity);

        $form->handleRequest($this->request);

        return $form;
    }

    protected function onSubmittedTrue(): void
    {
    }

    protected function onSubmittedFalse(): void
    {
    }

    protected function onValid(): void
    {
    }

    protected function onInvalid(): void
    {
    }

    protected function onBeforeValid(): void
    {
    }

    protected function onAfterValid(): void
    {
    }
}
