<?php

declare(strict_types=1);

namespace App\Validation\Validator\Cuman;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UrlValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null !== $value && !filter_var($value, FILTER_VALIDATE_URL)) {
            $url = 'http://'.$value;
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                $this->context->buildViolation('Invalid URL format')
                    ->atPath('website')
                    ->addViolation();
            }
        }
    }
}
