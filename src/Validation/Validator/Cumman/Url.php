<?php


namespace App\Validation\Validator\Cumman;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Url extends Constraint
{
    public $message = 'Host {{ host }} is not allowed.';
}