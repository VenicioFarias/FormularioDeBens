<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioBensDto extends Model
{
    protected $table = 'formulario_bens_dto';
    protected $primaryKey = 'id';
    protected $fillable = [

        'nome',
        'matricula',
        'cpf',
        'cargo',
        'lotacao',
        'telefone',
        'email',
        'data_cadastro'
    ];

    public $timestamps = false;
}
