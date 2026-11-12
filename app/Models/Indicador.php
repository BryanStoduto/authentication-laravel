<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Indicador extends Model
{
    protected $table = 'indicadores';

    protected $fillable = [
        'user_id',
        'codigo',
        'nome',
        'url_api'
    ];

    public function valores(): HasMany
    {
        return $this->hasMany(Valor::class, 'indicador_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
