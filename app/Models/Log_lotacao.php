<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Log_lotacao extends Model
{
    //use SoftDeletes;

    protected $connection = 'mysql_freq';
    protected $table = 'log_frequencias_lotacoes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'tabela',
        'operacao',
        'descricao',
        'created_at',
    ];
    public $timestamps = false;
}
