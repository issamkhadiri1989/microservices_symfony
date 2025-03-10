<?php

declare(strict_types=1);

namespace App\Password\Hasher;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final class UserPasswordHasher
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function hash(PasswordAuthenticatedUserInterface $user, #[\SensitiveParameter] string $rawPassword): string
    {
        return $this->hasher->hashPassword($user, $rawPassword);
    }
}
