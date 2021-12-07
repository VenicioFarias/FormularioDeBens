<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use App\Models\Lotacao;
use App\Models\Unidade;
use App\Models\Matricula;
use Carbon\Carbon;
use DateTime;
use GrahamCampbell\ResultType\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

use function GuzzleHttp\Promise\all;

class LotacaoController extends Controller
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
        return view('Lotacoes/lotacaoServidor', compact('unidades', 'funcoes', 'gratificacoes'));
        //  return view('lotacaoServidor');

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
        // dd($request->all());
        $finalizaLotacaoAnterior = Lotacao::find($request->lotacao_id_anterior);


            if ($request->a_partir_ini != null || $request->notificacao_ini != null) {

                if ($request->a_partir_ini > $request->notificacao_ini) {

                    $finalizaLotacaoAnterior->a_partir_de_fim  =  DateTime::createFromFormat('Y-m-d', $request->a_partir_ini)->format('Y-m-d');
                    $finalizaLotacaoAnterior->a_partir_de_fim = date('Y-m-d', strtotime('-1 days', strtotime($finalizaLotacaoAnterior->a_partir_de_fim)));

                }else
                {
                    $finalizaLotacaoAnterior->a_partir_de_fim  = $request->notificacao_ini ?  DateTime::createFromFormat('Y-m-d', $request->notificacao_ini)->format('Y-m-d') : null;
                }

            }

        $finalizaLotacaoAnterior->notificacao_fim = $request->notificacao_ini ?  DateTime::createFromFormat('Y-m-d', $request->notificacao_ini)->format('Y-m-d') : null;
        $finalizaLotacaoAnterior->diario_oficial_fim = $request->diario_ini ? DateTime::createFromFormat('Y-m-d', $request->diario_ini)->format('Y-m-d') : null;
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
        $atualizaLotacao->diario_oficial_ini = $request->diario_ini ? DateTime::createFromFormat('Y-m-d', $request->diario_ini)->format('Y-m-d') : null;
        $atualizaLotacao->a_partir_de_inicio = $request->a_partir_ini ? DateTime::createFromFormat('Y-m-d', $request->a_partir_ini)->format('Y-m-d') : null;
        $atualizaLotacao->notificacao_ini    = $request->notificacao_ini ? DateTime::createFromFormat('Y-m-d', $request->notificacao_ini)->format('Y-m-d') : null;
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
        // $finaliza = true;
        $query = "SELECT id FROM lotacoes where pessoa_id = $request->pessoa_id and a_partir_de_fim is null";
        $result = DB::connection('mysql_rh')->select(DB::raw($query));


        // dd($result,$finalizaLotacao);

        if ($result) {

            $finalizaLotacao = Lotacao::find($result[0]->id);

            // $finalizaLotacao->a_partir_de_fim  = $request->a_partir_ini ? DateTime::createFromFormat('Y-m-d', $request->a_partir_ini)->format('Y-m-d') : null;
            // if($finalizaLotacao->a_partir_de_fim == null){

                if ($request->a_partir_ini != null || $request->notificacao_ini != null) {

                    if ($request->a_partir_ini > $request->notificacao_ini) {

                        $finalizaLotacao->a_partir_de_fim  =  DateTime::createFromFormat('Y-m-d', $request->a_partir_ini)->format('Y-m-d');
                        $finalizaLotacao->a_partir_de_fim = date('Y-m-d', strtotime('-1 days', strtotime($finalizaLotacao->a_partir_de_fim)));

                    }else
                    {
                        $finalizaLotacao->a_partir_de_fim  = $request->notificacao_ini ?  DateTime::createFromFormat('Y-m-d', $request->notificacao_ini)->format('Y-m-d') : null;
                    }

                }


                $finalizaLotacao->notificacao_fim = $request->notificacao_ini ? DateTime::createFromFormat('Y-m-d', $request->notificacao_ini)->format('Y-m-d') : null;
                $finalizaLotacao->diario_oficial_fim = $request->diario_ini ? DateTime::createFromFormat('Y-m-d', $request->diario_ini)->format('Y-m-d') : null;
                $finalizaLotacao->processo_fim  = $request->a_processo_ini;
                $finalizaLotacao->pagina_do_fim = $request->pagina_ini;
                $finalizaLotacao->ato_fim = $request->ato_ini;


        // }

        // elseT
        // {

        //     $finaliza = false;

        // }
        // dump($finaliza);
        // dd($finalizaLotacao);
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

        DB::connection('mysql_rh')->transaction(function () use ($finalizaLotacao, $novaLotacao,$result) {
            // dd('finaliza',$finalizaLotacao);
            if ($result) {
                $finalizaLotacao->update();
                $novaLotacao->save();
            } else {
                $novaLotacao->save();
            }
            // Lotacao::findOrFail($request->lotacao_id)->update($finalizaLotacao);
        });

        return redirect()->route('lotacao.servidor')->with('msg', 'Nova Lotação Realizada com Sucesso!!');
     }

    }

    public function buscarPessoa(Request $request)
    {
        $result = false;
        $lotacaoData = '';
        // dd($request->all());
        $data = date('Y-m-d');

        $matricula = Matricula::with('pessoa')
            ->where('matricula', 'like', $request->input('matricula'))
            ->where('posse', '<=', $data)
            ->orderBy('posse', 'desc')
            ->first();


        // dd($matricula);
        if ($matricula != null) {
            $result = true;
            $pessoa_id = $matricula->pessoa_id;

            $historicoLotacao =  DB::connection('mysql_rh')->select("call get_historicoLotacao($pessoa_id)");

            $cargoAtual = DB::connection('mysql_rh')->select("call get_cargo_atual($pessoa_id)");
            // dd($cargoAtual );
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
            }
            else
            {
                if (empty($getLotacaoAtual)) {
                    $lotacaoAtual_nomeUnidade = $lotacao_ultimoRegistro[0]->unidade;
                    $lotacaoAtual_id = $lotacao_ultimoRegistro[0]->id_lotacao;
                } else {
                    $lotacaoAtual_id = $getLotacaoAtual[0]->id;

                    $lotacaoAtual_nomeUnidade = $getLotacaoAtual[0]->unidade;


                    if($getLotacaoAtual[0]->a_partir_de_inicio != null || $getLotacaoAtual[0]->a_partir_de_inicio > 0){
                        $lotacaoData =  $getLotacaoAtual[0]->a_partir_de_inicio;
                    }
                    // dd($lotacaoData);
                }

                if (!empty($lotacao_ultimoRegistro[0]->a_partir_de_inicio)) {
                    $lotacao_ultimoRegistro[0]->a_partir_de_inicio = DateTime::createFromFormat('Y-m-d', $lotacao_ultimoRegistro[0]->a_partir_de_inicio)->format('d/m/Y');
                }
                if (!empty($lotacao_ultimoRegistro[0]->diario_oficial_ini)) {
                    $lotacao_ultimoRegistro[0]->diario_oficial_ini = DateTime::createFromFormat('Y-m-d', $lotacao_ultimoRegistro[0]->diario_oficial_ini)->format('d/m/Y');
                }


                if (!empty($lotacao_ultimoRegistro[0]->notificacao_ini)) {
                    $lotacao_atual_nome = $lotacao_ultimoRegistro[0]->unidade;
                    $inserir_atualizar = true;
                    // dd($inserir_atualizar);
                } else {
                    $lotacao_atual_nome = $lotacaoAtual_nomeUnidade;
                    $inserir_atualizar = false;
                    // dd($inserir_atualizar);
                    $lotacao_ultimoRegistro[0]->a_partir_de_inicio = $lotacao_ultimoRegistro[0]->a_partir_de_inicio  ? DateTime::createFromFormat('d/m/Y',$lotacao_ultimoRegistro[0]->a_partir_de_inicio)->format('Y-m-d') : null;
                    $lotacao_ultimoRegistro[0]->notificacao_ini =  $lotacao_ultimoRegistro[0]->notificacao_ini ? DateTime::createFromFormat('d/m/Y',$lotacao_ultimoRegistro[0]->notificacao_ini)->format('Y-m-d'): null;
                    $lotacao_ultimoRegistro[0]->diario_oficial_ini = $lotacao_ultimoRegistro[0]->diario_oficial_ini ? DateTime::createFromFormat('d/m/Y',$lotacao_ultimoRegistro[0]->diario_oficial_ini)->format('Y-m-d'): null;
                }

                // if ($getLotacaoAtual[0]->a_partir_de_fim < $data) {
                //     $inserir_atualizar = true;
                // }

                $lotacao = [
                    'idLotacaoAtual' =>  $lotacaoAtual_id,
                    'dataInicioLotacaoAtual' => $lotacaoData,
                    'unidadeAtual' => $lotacaoAtual_nomeUnidade,
                    'pessoa' =>  $matricula->pessoa,
                    'UltimaLotacao' => $lotacao_ultimoRegistro[0],
                    'cargo' => $cargoAtual[0]->cargo,
                    'historicoLotacao' => $historicoLotacao,
                    'inserir_atualizar' => $inserir_atualizar
                ];

            }
        }

        // dd($lotacao );
        if ($result) {

            return response()->json($lotacao);

        }
        else {
            // dd('teste de entrada');
            return response()->json(['error' => true, 'message' => 'Pessoa não encontrada!']);
        }
    }

public function excluirLotacao(Request $request)
{
    // dd($request->all());
    $ultimoFinalizado = DB::connection('mysql_rh')->select('SELECT id,pessoa_id,a_partir_de_fim FROM rh.lotacoes
    where a_partir_de_fim is not null and pessoa_id= '.$request->pessoa_id.'
    order by created_at desc
    limit 1');
    // dd($ultimoFinalizado);
    $atualizaLotacaoAnterior = Lotacao::find($ultimoFinalizado[0]->id);
    $atualizaLotacaoAnterior->diario_oficial_fim = null;
    $atualizaLotacaoAnterior->pagina_do_fim = null;
    $atualizaLotacaoAnterior->ato_fim = null;
    $atualizaLotacaoAnterior->a_partir_de_fim = null;
    $atualizaLotacaoAnterior->notificacao_fim = null;
    // dd($atualizaLotacaoAnterior);
    $deletarLotacao = Lotacao::find($request->lotacao_id);

    $atualizaLotacaoAnterior->update();
    $deletarLotacao->delete();



    return redirect()->route('lotacao.servidor')->with('msg', 'Registro Deletado!!');



}

public function buscarPeriodo(Request $request)
{
    $result = false;
    // dd($request->all());
    $periodoLotacao = DB::connection('mysql_rh')->table('lotacoes')

    ->Where(function($query) use($request) {
        $query->where('a_partir_de_inicio', '>=',$request->input('data_a_partir'))
        ->orWhere('notificacao_ini','>=', $request->input('data_a_partir'));
    })->where('pessoa_id',$request->input('pessoa'))
    ->get();
    // dd($periodoLotacao);
    if ( $periodoLotacao->count() ) {
        // || $periodoLotacao != null || $periodoLotacao != '' || !isset($periodoLotacao) || $periodoLotacao->count()<= 0
        $result = true;
    }

    if ( $result) {
    //   dd($result);
        return response()->json(['error' => true, 'message' => 'Possui Lotação nesse Periodo!']);
    }else{
        // dd($result);
        return response()->json(['success' => 200]);
    }

}

}
