<?php

namespace dnj\UserLogger\Contracts;

use dnj\AAA\Contracts\IOwnerableModel;
use Illuminate\Database\Eloquent\Model;

interface ILog extends IOwnerableModel
{
    public function getId(): ?int;

    public function isAnonymous(): bool;

    public function getSubject(): ?Model;

    public function getSubjectType(): ?string;

    public function getSubjectId(): string|int|null;

    public function getEvent(): string;

    public function getProperties(): mixed;

    public function getExtraProperty(string $propertyName, mixed $defaultValue = null): mixed;

    public function getCreatedAt(): \DateTimeInterface;
}
