<?php

namespace dnj\UserLogger\Models;

use dnj\UserLogger\Contracts\ILog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

class Log extends Model implements ILog
{
    public const UPDATED_AT = null;

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        $model = $this->getUserModel();
        if (null === $model) {
            throw new \Exception('No user model is configured under user-logger.user_model config');
        }

        return $this->belongsTo($model);
    }

    public function subject(): MorphTo
    {
        if (config('user-logger.subject_returns_soft_deleted_models')) {
            return $this->morphTo()->withTrashed();
        }

        return $this->morphTo();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Authenticatable
    {
        return $this->user;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function isAnonymous(): bool
    {
        return is_null($this->user_id);
    }

    public function getSubject(): ?Model
    {
        return $this->subject;
    }

    public function getSubjectType(): ?string
    {
        return $this->subject_type;
    }

    public function getSubjectId(): string|int|null
    {
        return $this->subject_id;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function getProperties(): mixed
    {
        return $this->properties;
    }

    public function getExtraProperty(string $propertyName, mixed $defaultValue = null): mixed
    {
        return Arr::get($this->properties, $propertyName, $defaultValue);
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->created_at;
    }
}
