<?php

declare(strict_types=1);

namespace App\Validation\Validator\Publisher;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PublisherUrl extends Constraint
{
    public $message = 'Host {{ host }} jest not allowed.';
}