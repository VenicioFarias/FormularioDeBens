<?php

namespace App\Observers;

use Auth;
use App\Models\Log_lotacao;
use App\Models\Log_usuario;

class LogObserver
{

    public function saved($model)
    {
        if ($model->wasRecentlyCreated == true)
            $action = 'Criado';
        else
            $action = 'Atualizado';

        if (Auth::check()) {
            Log_lotacao::create([
                'user_id' => Auth::id(),
                'tabela' => $model->getTable(),
                'operacao' =>  $action,
                'descricao' => json_encode($model),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function deleting($model)
    {
        $action = 'Excluído';

        if (Auth::check()) {
            Log_lotacao::create([
                'user_id' => Auth::id(),
                'tabela' => $model->getTable(),
                'operacao' =>  $action,
                'descricao' => json_encode($model),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

}
