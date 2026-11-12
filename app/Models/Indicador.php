<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Indicador extends Model
{
    protected $table = 'indicadores';

    protected $fillable = ['codigo', 'nome', 'url_api'];
    
    public function valores(): HasMany
    {
        return $this->hasMany(Valor::class, 'indicador_id');
    }
}