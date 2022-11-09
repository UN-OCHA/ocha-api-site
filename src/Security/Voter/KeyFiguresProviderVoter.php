<?php

namespace App\Security\Voter;

use App\Entity\KeyFigures;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class KeyFiguresProviderVoter extends Voter
{
    private $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject): bool
    {
        $supportsAttribute = in_array($attribute, [
            'KEY_FIGURES_UPSERT',
            'KEY_FIGURES_BATCH',
        ]);
        $supportsSubject = $subject instanceof KeyFigures;

        return $supportsAttribute && $supportsSubject;
    }

    /**
     * @param string $attribute
     * @param $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case 'KEY_FIGURES_UPSERT':
                /** @var \App\Entity\KeyFigures $subject */
                if (in_array($subject->getProvider(), $user->getCanWrite())) {
                    return true;
                }
                break;

            case 'KEY_FIGURES_BATCH':
                // @todo does not get called by POST.
                return !empty($user->getCanWrite());
            }

        return false;
    }
}