<?php

declare(strict_types=1);

namespace App\Validation\Validator\Publisher;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PublisherUrlValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (empty($value)) {
            $this->context->buildViolation('URL cannot be empty.')
                ->addViolation();

            return;
        }

        $urlParts = parse_url($value);

        if (!isset($urlParts['host'])) {
            $this->context->buildViolation('Invalid URL format.')
                ->addViolation();

            return;
        }

        if (!in_array($urlParts['host'], PublisherHosts::HOST)) {
            $this->context->buildViolation(PublisherUrl::message)
                ->setParameter('{{ host }}', $urlParts['host'])
                ->addViolation();
        }
    }
}
