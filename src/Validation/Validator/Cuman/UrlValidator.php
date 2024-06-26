<?php

declare(strict_types=1);

namespace App\Validation\Validator\Cuman;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UrlValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null === $value || empty($value)) {
            return;
        }

        $pattern = '/^(?:http(s)?:\/\/)?(?:www\.)?([^\s]+\.[^\s]+)$/i';
        if (!preg_match($pattern, $value)) {
            $this->context->buildViolation(Url::message)
              ->atPath('website')
              ->addViolation();
        }
    }
}
