<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    protected $connection = 'Geo';
    protected $table = 'unidades';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unidade',
        'sigla',
        'email',
        'tipo_unidade_id',
        'endereco_id',
        'ais_id',
        'id_unidade_superior',
        'cod_unidade_sigi',
        'data_criacao',
        'data_extincao',
        'created_at'
    ];
    public $timestamps = false;

    public function cotas_departamento()
    {
        return $this->hasOne(Cotas_departamento::class, 'unidade_id', 'id');
    }

    public function departamentosRaiz()
    {
        return $this->where('id', '<>', 569)
                    ->where(function($query) {
                        $query->where('id_unidade_superior', 569)//569 - PCCE
                            ->orWhere('id_unidade_superior', null);
                    });
    }

    public function departamentos()
    {
        //#mudar - ->where('data_criacao', '<=', date('Y-m-d'))
        // return $this->where('tipo_unidade_id', 3)
        //             ->where('data_criacao', '<=', date('Y-m-d'))
        //             ->where(function($query) {
        //                 $query->where('data_extincao', '>=', date('Y-m-d'))//569 - PCCE
        //                     ->orWhere('data_extincao', null);
        //             });//3-departamentos
        return $this->where('tipo_unidade_id', 3)
            //->where('data_criacao', '<=', date('Y-m-d'))
            ->where(function($query) {
                $query->where('data_extincao', '>=', date('Y-m-d'))//569 - PCCE
                    ->orWhere('data_extincao', null);
            });
    }

    public static function pais($ids)
    {
        $lista = [];

        foreach ($ids as $id)
        {
            $unidade = new Unidade;

            if ($lista)
            {
                $uni = $unidade->pai($id);
                if (!in_array(current($uni), $lista))
                    $lista = $uni + $lista;//array_merge($uni, $lista);
            }
            else
                $lista = $unidade->pai($id);
        }

        return $lista;
    }

    public function pai($id)
    {
        $unidade = Unidade::where('id', $id)->first(['id', 'id_unidade_superior', 'unidade']);

        if ( ($unidade->id_unidade_superior == 569) or ($unidade->id_unidade_superior == null) )//#mudar (null) quando o banco estiver preenchido não é pra ter o null
            return [$unidade->id => $unidade->unidade];

        return $this->pai($unidade->id_unidade_superior);
    }

}
