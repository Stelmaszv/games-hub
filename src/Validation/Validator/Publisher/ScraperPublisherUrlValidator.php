<?php

declare(strict_types=1);

namespace App\Validation\Validator\Publisher;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ScraperPublisherUrlValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        $urlParts = parse_url($value);

        if (!isset($urlParts['host'])) {
            $this->context
                ->buildViolation('invalidUrl')
                ->addViolation();

            return;
        }

        if (!in_array($urlParts['host'], PublisherHosts::HOST)) {
            $this->context->buildViolation(ScraperPublisherUrl::message)
                ->setParameter('{{ host }}', $urlParts['host'])
                ->addViolation();
        }
    }
}
