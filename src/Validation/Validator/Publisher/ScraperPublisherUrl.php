<?php

declare(strict_types=1);

namespace App\Validation\Validator\Publisher;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ScraperPublisherUrl extends Constraint
{
    public const message = 'invalidScraperUrl';
}
