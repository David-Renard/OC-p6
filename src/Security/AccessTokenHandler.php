<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\TokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private TokenRepository $tokenRepository,
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $accessToken = $this->tokenRepository->findOneByValue($accessToken);

        if ($accessToken === null) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return new UserBadge($accessToken->getUserInfo()->getUserIdentifier());
    }

    private function isCheck(?User $user, string $accessToken): bool
    {
        if ($user == null) {
            return false;
        }

        return $user->getUserIdentifier() === $this->getUserBadgeFrom($accessToken)->getUserIdentifier();
    }

    private function isInTime(string $accessToken): bool
    {
        $value = false;

        $token      = $this->tokenRepository->findOneByValue($accessToken);
        $dateToken  = $token->getCreatedAt();
        $now        = new \DateTimeImmutable();
        $interval   = $now->diff($dateToken);
        if ($interval->days <= 3) {
            $value = true;
        }

        return $value;
    }

    private function isActive(string $accesToken): bool
    {
        $token = $this->tokenRepository->findOneByValue($accesToken);

        return $token->getStatus() === "active";
    }

    private function isWellTyped(string $accesToken, string $value): bool
    {
        $token = $this->tokenRepository->findOneByValue($accesToken);
        $type  = $token->getType();

        return $type === $value;
    }

    public function isValid(string $accesToken, ?User $user, string $type): bool
    {
        $return = false;

        if ($this->isCheck($user, $accesToken)
            && $this->isInTime($accesToken)
            && $this->isActive($accesToken)
            && $this->isWellTyped($accesToken, $type)
        ) {
            $return = true;
        }

        return $return;
    }
}
