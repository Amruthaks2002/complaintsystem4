<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class);
    }
}
