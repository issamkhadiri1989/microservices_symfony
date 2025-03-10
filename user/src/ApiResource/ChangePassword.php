<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Password\Processor\PasswordUpdaterProcessor;
use App\Security\Constraint\CurrentUserPassword;
use Symfony\Component\Validator\Constraints\IdenticalTo;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/user/change-password',
            processor: PasswordUpdaterProcessor::class,
            output: false,
        ),
    ],
)]
class ChangePassword
{
    #[CurrentUserPassword]
    public string $currentPassword;

    #[IdenticalTo(propertyPath: 'repeatedPassword', message: 'The passwords should be identical.')]
    public string $newPassword;

    public string $repeatedPassword;
}
