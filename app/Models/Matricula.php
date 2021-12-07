<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $connection = 'mysql_rh';
    protected $table = 'matriculas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'matricula',
        'processo_nomeacao',
        'ato_nomeacao',
        'do_nomeacao',
        'pagina_nomeacao',
        'posse','exercicio',
        'processo_exoneracao',
        'ato_exoneracao',
        'do_exoneracao',
        'pagina_exoneracao',
        'pessoa_id',
        'created_at'
    ];
    public $timestamps = false;

    public static function pessoaId($matricula, $data = null)
    {
        if (!$data)
            $data = date('Y-m-d');

        $mat = Matricula::where('matricula', 'like', $matricula)->where('posse', '<=', $data)->orderBy('posse', 'desc')->first(['pessoa_id']);

        if ($mat)
            return $mat->pessoa_id;

        return null;
    }

    public function pessoa()
    {
        return $this->hasOne(Pessoa::class, 'id', 'pessoa_id');
    }
}
