<?php

namespace dnj\UserLogger\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface ILogger
{
    public function causedBy(string|int|Authenticatable|null $user): self;

    public function causedByAnonymous(): self;

    public function byAnonymous(): self;

    public function by(string|int|Authenticatable|null $user): self;

    public function on(?Model $subject): self;

    public function performedOn(?Model $subject): self;

    public function event(string $event): self;

    public function withIP(?string $ip): self;

    public function withProperties(?array $properties): self;

    public function withProperty(string $key, mixed $value): self;

    public function createdAt(?\DateTimeInterface $dateTime): self;

    public function withRequest(?Request $request, bool $captureIP = true, bool $captureUser = true): self;

    public function build(): ILog;

    public function log(string $event = null): ILog;
}
