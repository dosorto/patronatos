<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $connection = 'mysql';
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'db_connection',
        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function activos(): HasMany
    {
        return $this->hasMany(Activo::class, 'organizacion_id');
    }
}
