<?php

declare(strict_types=1);

namespace App\Service\Validation;

class PasswordChecker
{
    /**
     * @var array<string>
     */
    private array $specialCharacters = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '+', '='];

    private function estimatePasswordStrength(string $password): int
    {
        $score = 0;

        $length = strlen($password);
        if ($length >= 12) {
            $score += 3;
        } elseif ($length >= 8) {
            $score += 2;
        } else {
            ++$score;
        }

        foreach ($this->specialCharacters as $character) {
            if (false !== strpos($password, $character)) {
                $score += 2;
                break;
            }
        }

        if (preg_match('/[a-z]/', $password)) {
            $score += 2;
        }

        if (preg_match('/[A-Z]/', $password)) {
            $score += 2;
        }

        return $score;
    }

    public function checkPasswordStrength(string $password): string
    {
        $strength = $this->estimatePasswordStrength($password);
        if ($strength >= 8) {
            $error = 'strong';
        } elseif ($strength >= 5) {
            $error = 'medium';
        } else {
            $error = 'weak';
        }

        return $error;
    }
}
