<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use App\Models\Lotacao;
use App\Models\Unidade;
use App\Models\Matricula;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LotacaoNovaController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $funcoes = DB::connection('mysql_rh')->table('funcoes')->where('data_extincao')->get();
        $gratificacoes = DB::connection('mysql_rh')->table('gratificacoes')->where('data_fim')->get();
        $unidades = Unidade::get();
        // return view('Lotacoes/lotacaoModal', compact('unidades', 'funcoes', 'gratificacoes'));
        return view('Lotacoes/modalLotacao', compact('unidades', 'funcoes', 'gratificacoes'));

    }
    public function historicoLotacao()
    {
        // $funcoes= DB::connection('mysql_rh')->table('funcoes')->where('data_extincao')->get();
        // $gratificacoes = DB::connection('mysql_rh')->table('gratificacoes')->where('data_fim')->get();
        // dd($gratificacoes);
        // $unidades = Unidade::get();
        // return view('Lotacoes/lotacaoServidor',compact('unidades','funcoes','gratificacoes'));
        return view('Lotacoes.historicoLotacao');
    }
    public function atualizarLotacao(Request $request)
    {
        // dump($request->all());
        $finalizaLotacaoAnterior = Lotacao::find($request->lotacao_id_anterior);
        // $finalizaLotacaoAnterior->a_partir_de_fim  = $request->a_partir_ini ?  DateTime::createFromFormat('d/m/Y', $request->a_partir_ini)->format('Y-m-d') : null;
        if ($request->a_partir_ini != null) {

            $finalizaLotacaoAnterior->a_partir_de_fim  = $request->a_partir_ini ?  DateTime::createFromFormat('d/m/Y', $request->a_partir_ini)->format('Y-m-d') : null;
        }
        else
        {
            $finalizaLotacaoAnterior->a_partir_de_fim  = $request->notificacao_ini ?  DateTime::createFromFormat('d/m/Y', $request->notificacao_ini)->format('Y-m-d') : null;
        }
        if ( $finalizaLotacaoAnterior->a_partir_de_fim != null)
        {
            $finalizaLotacaoAnterior->a_partir_de_fim = date('Y-m-d', strtotime('-1 days', strtotime($finalizaLotacaoAnterior->a_partir_de_fim)));
        }
        $finalizaLotacaoAnterior->notificacao_fim = $request->notificacao_ini ?  DateTime::createFromFormat('d/m/Y', $request->notificacao_ini)->format('Y-m-d') : null;
        $finalizaLotacaoAnterior->diario_oficial_fim = $request->diario_ini ? DateTime::createFromFormat('d/m/Y', $request->diario_ini)->format('Y-m-d') : null;
        $finalizaLotacaoAnterior->processo_fim  = $request->a_processo_ini;
        $finalizaLotacaoAnterior->pagina_do_fim = $request->pagina_ini;
        $finalizaLotacaoAnterior->ato_fim = $request->ato_ini;
        // dump($request->all());
        // dd($finalizaLotacaoAnterior);

        //lotação atual---------------------------------------------------------------------------
        $atualizaLotacao = Lotacao::find($request->lotacao_id);
        $atualizaLotacao->pessoa_id = $request->pessoa_id;
        $atualizaLotacao->unidade_id = $request->novaUnidade_id;
        $atualizaLotacao->gratificacao_id = $request->gratifica_id;
        $atualizaLotacao->funcao_id = $request->funcao_id;
        $atualizaLotacao->processo_ini = $request->processo_ini;
        $atualizaLotacao->ato_ini = $request->ato_ini;
        $atualizaLotacao->pagina_do_ini = $request->pagina_ini;
        $atualizaLotacao->diario_oficial_ini = $request->diario_ini ? DateTime::createFromFormat('d/m/Y', $request->diario_ini)->format('Y-m-d') : null;
        $atualizaLotacao->a_partir_de_inicio = $request->a_partir_ini ? DateTime::createFromFormat('d/m/Y', $request->a_partir_ini)->format('Y-m-d') : null;
        $atualizaLotacao->notificacao_ini    = $request->notificacao_ini ? DateTime::createFromFormat('d/m/Y', $request->notificacao_ini)->format('Y-m-d') : null;
        $atualizaLotacao->observacao = $request->obs;
        // $request->diario_ini ? DateTime::createFromFormat('d/m/Y', $request->diario_ini)->format('Y-m-d') : null;
        // dd($atualizaLotacao);
        // $finalizaLotacaoAnterior->update();
        // $atualizaLotacao->save();
        DB::connection('mysql_rh')->transaction(function () use ($finalizaLotacaoAnterior, $atualizaLotacao) {
            // dd($finalizaLotacaoAnterior);
            $finalizaLotacaoAnterior->update();
            $atualizaLotacao->update();
        });
        return redirect()->route('lotacao.servidor')->with('msg', 'Registro Atualizado com Sucesso!!');
    }
    public function salvarLotacao(Request $request)
    {

        // dd($request->all());
        // if ($request->lotacaoAnterior_id != null) {

        //     $finalizaLotacao = Lotacao::find($request->lotacaoAnterior_id);
        //     $finalizaLotacao->a_partir_de_fim  = $request->a_partir_ini ? DateTime::createFromFormat('d/m/Y', $request->a_partir_ini)->format('Y-m-d') : null;
        //     $finalizaLotacao->a_partir_de_fim =    date('Y-m-d', strtotime('-1 days', strtotime($finalizaLotacao->a_partir_de_fim)));
        //     $finalizaLotacao->notificacao_fim = $request->notificacao_ini ?  DateTime::createFromFormat('d/m/Y', $request->notificacao_ini)->format('Y-m-d') : null;
        //     $finalizaLotacao->diario_oficial_fim = $request->diario_ini ? DateTime::createFromFormat('d/m/Y', $request->diario_ini)->format('Y-m-d') : null;
        //     $finalizaLotacao->processo_fim  = $request->a_processo_ini;
        //     $finalizaLotacao->pagina_do_fim = $request->pagina_ini;
        //     $finalizaLotacao->ato_fim = $request->ato_ini;
        // }

        if ($request->lotacaoAnterior_id != null) {

            $finalizaLotacao = Lotacao::find($request->lotacaoAnterior_id);
            $finalizaLotacao->a_partir_de_fim  = $request->a_partir_ini ? DateTime::createFromFormat('Y-m-d', $request->a_partir_ini)->format('Y-m-d') : null;
            $finalizaLotacao->a_partir_de_fim =    date('Y-m-d', strtotime('-1 days', strtotime($finalizaLotacao->a_partir_de_fim)));
            $finalizaLotacao->notificacao_fim = $request->notificacao_ini ?  DateTime::createFromFormat('Y-m-d', $request->notificacao_ini)->format('Y-m-d') : null;
            $finalizaLotacao->diario_oficial_fim = $request->diario_ini ? DateTime::createFromFormat('Y-m-d', $request->diario_ini)->format('Y-m-d') : null;
            $finalizaLotacao->processo_fim  = $request->a_processo_ini;
            $finalizaLotacao->pagina_do_fim = $request->pagina_ini;
            $finalizaLotacao->ato_fim = $request->ato_ini;
        }

        else
        {
            $finalizaLotacao = null;
        }

        // dd($finalizaLotacao);
        $novaLotacao = new Lotacao;

        // $novaLotacao = Lotacao::updateOrCreate(['id_lotacao'=> $request->lotacao_id,'pessoa_id'=>$request->pessoa_id]);
        $novaLotacao->pessoa_id = $request->pessoa_id;
        $novaLotacao->unidade_id = $request->novaUnidade_id;
        $novaLotacao->gratificacao_id = $request->gratifica_id;
        $novaLotacao->funcao_id = $request->funcao_id;
        $novaLotacao->processo_ini = $request->processo_ini;
        $novaLotacao->ato_ini = $request->ato_ini;
        $novaLotacao->pagina_do_ini = $request->pagina_ini;
        $novaLotacao->diario_oficial_ini = $request->diario_ini ? DateTime::createFromFormat('Y-m-d', $request->diario_ini)->format('Y-m-d') : null;
        $novaLotacao->a_partir_de_inicio = $request->a_partir_ini ? DateTime::createFromFormat('Y-m-d', $request->a_partir_ini)->format('Y-m-d') : null;
        $novaLotacao->notificacao_ini    = $request->notificacao_ini ? DateTime::createFromFormat('Y-m-d', $request->notificacao_ini)->format('Y-m-d') : null;
        $novaLotacao->observacao = $request->obs;

        DB::connection('mysql_rh')->transaction(function () use ($finalizaLotacao, $novaLotacao) {
            // dd('finaliza',$finalizaLotacao);
            if ($finalizaLotacao != null) {
                $finalizaLotacao->update();
                $novaLotacao->save();
            } else {
                $novaLotacao->save();
            }
            // Lotacao::findOrFail($request->lotacao_id)->update($finalizaLotacao);
        });

        return redirect()->route('lotacao.servidor')->with('msg', 'Nova Lotação Realizada com Sucesso!!');
    }




    public function buscarPessoa(Request $request)
    {
        // dd($request->all());
        $data = date('Y-m-d');

        $matricula = Matricula::with('pessoa')
            ->where('matricula', 'like', $request->input('matricula'))
            ->where('posse', '<=', $data)
            ->orderBy('posse', 'desc')
            ->first();
        // dd($matricula);
        $pessoa_id = $matricula->pessoa_id;
        $cargoAtual = DB::connection('mysql_rh')->select("call get_cargo_atual($pessoa_id)");
        // dd($cargoAtual);
        if ($matricula) {
            // dd($id_servidor);
            $lotacao_ultimoRegistro = DB::connection('mysql_rh')->select("call get_lotacao($pessoa_id)");

            $getLotacaoAtual = DB::connection('mysql_rh')->select("call get_lotacao_atual($pessoa_id)");
            // dump($getLotacaoAtual);
            // dd($lotacao_ultimoRegistro);
            if (empty($getLotacaoAtual) && empty($lotacao_ultimoRegistro)) {
                // dd($matricula);
                $lotacao = [
                    'idLotacaoAtual' =>  '',
                    'unidadeAtual' => 'Não Possui Nenhuma Lotação',
                    'pessoa' =>  $matricula->pessoa,
                    'UltimaLotacao' => '',
                    'cargo' => $cargoAtual[0]->cargo,
                    'inserir_atualizar' => true
                ];
            } else {
                if (empty($getLotacaoAtual)) {

                    $lotacaoAtual_nomeUnidade = $lotacao_ultimoRegistro[0]->unidade;
                    $lotacaoAtual_id = $lotacao_ultimoRegistro[0]->id_lotacao;
                } else {
                    $lotacaoAtual_id = $getLotacaoAtual[0]->id;

                    $lotacaoAtual_nomeUnidade = $getLotacaoAtual[0]->unidade;
                }

                if (!empty($lotacao_ultimoRegistro[0]->a_partir_de_inicio)) {
                    $lotacao_ultimoRegistro[0]->a_partir_de_inicio = DateTime::createFromFormat('Y-m-d', $lotacao_ultimoRegistro[0]->a_partir_de_inicio)->format('d/m/Y');
                }
                if (!empty($lotacao_ultimoRegistro[0]->diario_oficial_ini)) {
                    $lotacao_ultimoRegistro[0]->diario_oficial_ini = DateTime::createFromFormat('Y-m-d', $lotacao_ultimoRegistro[0]->diario_oficial_ini)->format('d/m/Y');
                }
                // $lotacao_ultimoRegistro[0]->diario_oficial_ini = DateTime::createFromFormat('Y-m-d',$lotacao_ultimoRegistro[0]->diario_oficial_ini)->format('d/m/Y');
                // $lotacao_ultimoRegistro[0]->a_partir_de_inicio = DateTime::createFromFormat('Y-m-d',$lotacao_ultimoRegistro[0]->a_partir_de_inicio)->format('d/m/Y');
                // dd( $lotacao_ultimoRegistro[0]->a_partir_de_inicio);
                $lotacao_atual_nome = '';

                if (!empty($lotacao_ultimoRegistro[0]->notificacao_ini)) {
                    $lotacao_atual_nome = $lotacao_ultimoRegistro[0]->unidade;
                    $inserir_atualizar = true;
                    // dd($inserir_atualizar);
                } else {
                    $lotacao_atual_nome = $lotacaoAtual_nomeUnidade;
                    $inserir_atualizar = false;
                    // dd($inserir_atualizar);
                }


                $lotacao = [
                    'idLotacaoAtual' =>  $lotacaoAtual_id,
                    'unidadeAtual' => $lotacaoAtual_nomeUnidade,
                    'pessoa' =>  $matricula->pessoa,
                    'UltimaLotacao' => $lotacao_ultimoRegistro[0],
                    'cargo' => $cargoAtual[0]->cargo,
                    'inserir_atualizar' => $inserir_atualizar
                ];
            }
        }

        // dd($lotacao );
        if ($lotacao) {
            // dd($matricula);
            return response()->json($lotacao);
        } else {
            //    dd('teste de entrada');
            return response()->json(['error' => true, 'message' => 'Pessoa não encontrada!']);
        }
    }


public function error()
{
    $exception = 'error';
    $mensagem ='teste';
    return view('paginaErrorCustom',compact('exception','mensagem'));
}

}
