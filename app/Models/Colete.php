<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colete extends Model
{

    //protected $connection = 'colete';
    protected $table = 'coletes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'marca',
        'tamanho',
        'sexo',
        'num_serie',
        'form_id',
        'created_at'
    ];
    public $timestamps = false;
}
