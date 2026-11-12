<?php

namespace App\Models;

use App\Models\Valor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Valor extends Model
{
    protected $table = 'valores';

    public $timestamps = false;

    protected $fillable = ['indicador_id', 'data', 'valor'];

    public function indicador(): BelongsTo
    {
        return $this->belongsTo(Indicador::class, 'indicador_id');
    }
}