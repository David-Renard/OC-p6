<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\TokenRepository;

class Token
{
    public function __construct(
        private TokenRepository $tokenRepository,
    ) {
    }

    private function isCheck(?User $user, string $accessToken): bool
    {
        if ($user == null) {
            return false;
        }

        return $user->getUserIdentifier() === $this->tokenRepository->findOneByValue($accessToken)->getUserInfo()->getUserIdentifier();
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

    public function isAwaiting(string $accesToken): bool
    {
        $token = $this->tokenRepository->findOneByValue($accesToken);

        return $token->getStatus() === "waiting";
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
            && $this->isWellTyped($accesToken, $type)
        ) {
            $return = true;
        }

        return $return;
    }
}
