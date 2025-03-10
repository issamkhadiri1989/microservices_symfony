<?php

declare(strict_types=1);

namespace App\Security\Password;

use App\Entity\User;

interface UserPasswordUpdaterInterface
{
    public function updatePassword(User $user, #[\SensitiveParameter] string $plainPassword): void;
}
