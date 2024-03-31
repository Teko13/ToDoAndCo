<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    public const EDIT = 'CAN_EDIT';
    public const DELETE = "CAN_DELETE";

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var Task $subject
         */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return ($subject->getAuthor() === $user || in_array("ROLE_ADMIN", $user->getRoles()));
            case self::DELETE:
                return
                    (
                        $subject->getAuthor() === $user ||
                        (in_array("ROLE_ADMIN", $user->getRoles()) && $subject->getAuthor()->getUsername() === "anonymous")
                    );
        }

        return false;
    }
}
