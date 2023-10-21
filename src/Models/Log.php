<?php

namespace dnj\UserLogger\Models;

use dnj\AAA\Contracts\IUser;
use dnj\AAA\HasOwner;
use dnj\AAA\Models\User;
use dnj\UserLogger\Contracts\ILog;
use dnj\UserLogger\Database\Factories\LogFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

class Log extends Model implements ILog
{
    use HasOwner;
    use HasFactory;

    public static function newFactory(): LogFactory
    {
        return LogFactory::new();
    }


    public const UPDATED_AT = null;

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        if (config('user-logger.subject_returns_soft_deleted_models')) {
            return $this->morphTo()->withTrashed();
        }

        return $this->morphTo();
    }

    /**
     * @param array{id?:int,event?:string|string[],user?:int|IUser|null,subject?:Model|string|array{type:string,id?:string}|null,ip?:string|string[]|null} $filters
     */
    public function scopeFilter(Builder $query, array $filters) {
        if (isset($filters['id'])) {
            $query->where("id", $filters['id']);
        }
        if (isset($filters['event'])) {
            $this->scopeWithEvent($query, $filters['event']);
        }
        if (array_key_exists("user", $filters)) {
            $this->scopeWithUser($query, $filters['user']);
        }
        if (array_key_exists("subject", $filters)) {
            $this->scopeWithSubject($query, $filters['subject']);
        }
        if (array_key_exists("ip", $filters)) {
            $this->scopeWithIP($query, $filters['ip']);
        }
    }

    /**
     * @param string[]|string $event
     */
    public function scopeWithEvent(Builder $query, array|string $event): void {
        if (!is_array($event)) {
            $event = [$event];
        }
        $query->whereIn("event", $event);
    }

    /**
     * @param string[]|string|null $ip
     */
    public function scopeWithIP(Builder $query, array|string|null $ip): void {
        if ($ip === null) {
            $query->whereNull("ip");
            return;
        }
        if (!is_array($ip)) {
            $ip = [$ip];
        }
        $query->whereIn("ip", $ip);
    }

    public function scopeWithUser(Builder $query, int|IUser|null $user): void {
        if ($user === null) {
            $query->whereNull("user_id");
            return;
        }
        $user = User::ensureId($user);
        $query->where("user_id", $user);
    }

    /**
     * @param Model|string|array{type:string,id?:string}|null $subject
     */
    public function scopeWithSubject(Builder $query, Model|string|array|null $subject): void {
        if ($subject === null) {
            $query->whereNull("subject_type");
            return;
        }
        if (is_string($subject)) {
            $subject = array(
                'type' => $subject
            );
        } else if ($subject instanceof Model) {
            $subject = array(
                'type' => get_class($subject),
                'id' => $subject->getKey(),
            );
        }
        
        $query->where("subject_type", $subject['type']);
        if (isset($subject['id'])) {
            $query->where("subject_id", $subject['id']);
        }
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

    public function owner(): BelongsTo
    {
        return $this->user();
    }

    public function getOwnerUserId(): ?int
    {
        return $this->user_id;
    }

    public function getOwnerUserColumn(): string
    {
        return 'user_id';
    }
}
