<?php

namespace App\Validation\Validator\Cuman;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Url extends Constraint
{
    public const message = 'invalidUrl';
}
