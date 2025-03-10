<?php

declare(strict_types=1);

namespace App\Password\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Security\Password\UserPasswordUpdaterInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class PasswordUpdaterProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $processor,
        private readonly Security $security,
        private readonly UserPasswordUpdaterInterface $passwordUpdater,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if (null === $user) {
            throw new UnauthorizedHttpException('You are not authorized to perform this action.');
        }

        // change the current user's password.
        $this->passwordUpdater->updatePassword($user, $data->newPassword);
    }
}
