<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\User;
use DateTimeInterface;
use App\Data\Enums\AccessLevel;

readonly class IssueAccessTokenDto
{
    public string $name;

    public ?DateTimeInterface $expiresAt;

    public function __construct(
        public User $user,
        public AccessLevel $accessLevel,
        ?DateTimeInterface $expiresAt = null,
        ?string $name = null,
    ) {
        $this->name = $name ?? __('users-device-name', ['name' => $user->name]);
        $this->expiresAt = $this->parseExpiresAt($expiresAt);
    }

    private function parseExpiresAt(?DateTimeInterface $expiresAt): ?DateTimeInterface
    {
        if ($expiresAt) {
            return $expiresAt;
        }

        $ttl = intval(config('sanctum.expiration'));

        return $ttl > 0 ? now()->addMinutes($ttl) : null;
    }
}
