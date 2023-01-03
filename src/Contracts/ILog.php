<?php

namespace dnj\UserLogger\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

interface ILog
{
    public function getId(): ?int;

    public function getUser(): ?Authenticatable;

    public function getUserId(): ?int;

    public function isAnonymous(): bool;

    public function getSubject(): ?Model;

    public function getSubjectType(): ?string;

    public function getSubjectId(): string|int|null;

    public function getEvent(): string;

    public function getProperties(): mixed;

    public function getExtraProperty(string $propertyName, mixed $defaultValue = null): mixed;

    public function getCreatedAt(): \DateTimeInterface;
}
