<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargos extends Model
{
    protected $connection = 'mysql_rh';
    protected $table = 'cargos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cargo',
        'sigla_grande',
        'sigla_pequena',
        'cod_cargo_seplag',
        'criacao',
        'created_at',
    ];
    public $timestamps = false;


}
