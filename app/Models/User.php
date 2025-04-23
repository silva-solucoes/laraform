<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the forms created by the user.
     */
    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    /**
     * Get the forms where the user is a collaborator.
     */
    public function collaborationForms()
    {
        return $this->belongsToMany(Form::class, 'form_collaborators');
    }

    /**
     * Check if the user is a collaborator of the given form.
     *
     * @param int $formId
     * @return bool
     */
    public function isFormCollaborator($formId)
    {
        // Verifica se o usuário é colaborador do formulário
        return $this->formCollaborators()->where('form_id', $formId)->exists();
    }

    public function formCollaborators()
    {
        return $this->belongsToMany(Form::class, 'form_collaborators');
    }


    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }
}
