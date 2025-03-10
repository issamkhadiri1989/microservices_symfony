<?php

declare(strict_types=1);

namespace App\Security\Constraint;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class CurrentUserPasswordValidator extends ConstraintValidator
{
    public function __construct(
        private readonly Security $security,
        private readonly PasswordHasherFactoryInterface $hasherFactory,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof CurrentUserPassword) {
            throw new UnexpectedTypeException($constraint, CurrentUserPassword::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $user = $this->security->getUser();

        // this is to make sure that we can call the `getPassword()` method.
        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            throw new ConstraintDefinitionException(\sprintf('The "%s" class must implement the "%s" interface.', get_debug_type($user), PasswordAuthenticatedUserInterface::class));
        }

        // get the hasher current configured for the given User instance. see the `config/packages/security.yaml`.
        $hasher = $this->hasherFactory->getPasswordHasher($user);

        if (null === $user->getPassword() || !$hasher->verify($user->getPassword(), $value)) {
            $this->context->buildViolation($constraint->message)
                ->setCode(UserPassword::INVALID_PASSWORD_ERROR)
                ->addViolation();
        }
    }
}
