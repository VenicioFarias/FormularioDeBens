<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pessoa extends Model
{
    protected $connection = 'mysql_rh';
    protected $table = 'pessoas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'nome',
        'matricula',
        'folha',
        'estado_civil',
        'pai',
        'mae',
        'cpf',
        'pis_pasep',
        'grupo_sanguineo',
        'data_nascimento',
        'sexo_id',
        'cidade_id',
        'endereco_id',
        'created_at',
        'updated_at',
    ];
    public $timestamps = false;

    public function getMatriculaAttribute()
    {
        $matricula = $this->hasOne(Matricula::class, 'pessoa_id', 'id')->where('posse', '<=', date('Y-m-d'))->orderBy('posse', 'desc')->first(['matricula']);

        if ( $matricula )
            return $matricula->matricula;

        return '';
    }

    public function lotacaoAtual()
    {
        return $this->hasOne(Lotacao::class, 'pessoa_id', 'id')->where('a_partir_de_inicio', '<=', date('Y-m-d'))->orderBy('a_partir_de_inicio', 'desc');
    }

    public function autocomplete( Request $request ) {
        $pessoa = Pessoa::select( "nome" )
          ->where( "nome", "LIKE", "%{$request->input('query')}%" )
          ->get();
        return response()->json($pessoa);
     }
}
