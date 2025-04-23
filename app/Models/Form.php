<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany,
    HasOne,
    BelongsToMany
};
use App\Models\{
    Field,
    Response,
    FormCollaborator,
    FormAvailability,
    User
};

class Form extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected array $cascadeDeletes = ['fields', 'responses', 'collaborators'];

    protected $fillable = [
        'title',
        'description',
        'code',
        'status'
    ];

    protected $dates = ['deleted_at'];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function collaborators(): HasMany
    {
        return $this->hasMany(FormCollaborator::class);
    }

    public function collaborationUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'form_collaborators');
    }

    public function availability(): HasOne
    {
        return $this->hasOne(FormAvailability::class);
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function generateCode(): static
    {
        $this->code = Str::random(10);
        return $this;
    }

    public function shareFormViaMail(string $recipient_email, array $data): void
    {
        \Mail::to($recipient_email)->queue(new \App\Mail\ShareForm($this, $data));
    }

    public function isAvailable(): bool
    {
        if (!$this->availability) {
            return true;
        }

        $now = now();
        $start_date = $this->availability->start_date;
        $end_date = $this->availability->end_date;

        if ($start_date && $now->lt($start_date)) {
            return false;
        }

        if ($end_date && $now->gt($end_date)) {
            return false;
        }

        return true;
    }

    public function getStatusTextAttribute(): string
    {
        $statuses = [
            self::STATUS_DRAFT => 'Rascunho',
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_OPEN => 'Aberto',
            self::STATUS_CLOSED => 'Fechado',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            self::STATUS_DRAFT => 'default',
            self::STATUS_PENDING => 'warning',
            self::STATUS_OPEN => 'success',
            self::STATUS_CLOSED => 'danger',
        ];

        return $colors[$this->status] ?? 'default';
    }

    public static function getStatusSymbols(): array
    {
        return [
            self::STATUS_DRAFT => ['label' => 'Rascunho', 'color' => 'default'],
            self::STATUS_PENDING => ['label' => 'Pendente', 'color' => 'warning'],
            self::STATUS_OPEN => ['label' => 'Aberto', 'color' => 'success'],
            self::STATUS_CLOSED => ['label' => 'Fechado', 'color' => 'danger'],
        ];
    }
}
