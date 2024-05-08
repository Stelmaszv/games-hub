<?php

declare(strict_types=1);

namespace App\Validation\Validator\Publisher;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PublisherUrlValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return false;
        }

        $urlParts = parse_url($value);

        if (!isset($urlParts['host'])) {
            return false;
        }

        $urlParts = parse_url($value);

        if (!in_array($urlParts['host'], PublisherHosts::HOST)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ host }}', $urlParts['host'])
                ->addViolation();
        }
    }
}
