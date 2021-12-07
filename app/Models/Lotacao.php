<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lotacao extends Model
{
    protected $connection = 'mysql_rh';
    protected $table = 'lotacoes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'pessoa_id',
        'unidade_id',
        'gratificacao_id',
        'funcao_id',
        'processo_ini',
        'processo_fim',
        'ato_ini',
        'ato_fim',
        'diario_oficial_ini',
        'diario_oficial_fim',
        'pagina_do_ini',
        'pagina_do_fim',
        'a_partir_de_inicio',
        'a_partir_de_fim',
        'notificacao_ini',
        'notificacao_fim',
        'exercicio_ini',
        'observacao',
        'created_at',
        'updated_at'
    ];
    // protected $casts = [
    //     'a_partir_de_inicio' => 'datetime:d/m/Y',
    // ];

    public function getFromDateAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }
    public $timestamps = false;

    public function pessoa()
    {
        return $this->hasOne(Pessoa::class, 'id', 'pessoa_id');
    }
    public function getApartirDeBr()
    {
        return \Carbon\Carbon::parse( $this->a_partir_de )->format('d/m/Y');
    }


    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\LogObserver);
    }

}
