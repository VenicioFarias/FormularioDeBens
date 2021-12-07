<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Log_usuario extends Model
{
    //use SoftDeletes;

    protected $connection = 'Usuarios';
    protected $table = 'log_usuarios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'tabela',
        'descricao',
        'created_at',
    ];
    public $timestamps = false;
}
