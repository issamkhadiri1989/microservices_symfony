<?php

declare(strict_types=1);

namespace App\Security\Password;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DefaultUserPasswordUpdater implements UserPasswordUpdaterInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly EntityManagerInterface $manager,
    ) {
    }

    public function updatePassword(User $user, #[\SensitiveParameter] string $plainPassword): void
    {
        $hashedPassword = $this->hasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        $this->manager->flush();
    }
}
