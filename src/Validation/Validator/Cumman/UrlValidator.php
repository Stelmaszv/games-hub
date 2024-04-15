<?php

declare(strict_types=1);

namespace App\Validation\Validator\Cumman;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class UrlValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value !== null && !filter_var($value, FILTER_VALIDATE_URL)) {
            $url = "http://" . $value;
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                $this->context->buildViolation('Invalid URL format')
                    ->atPath('website')
                    ->addViolation();
            }

        }
    }
}
