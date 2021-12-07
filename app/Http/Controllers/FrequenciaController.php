<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrequenciaController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //INFO-PROV: substituir por informações dinamicas
        $mesAnoFrequencia = '05/2021';
        $mesFrequencia = 5;
        $anoFrequencia = 2021;
        $ultimaLotacao = 222;
        //fim INFO-PROV



        // Verifica se existe a sessão
        if ($request->session()->has('unidadeId')) {
            // $lotacao = $request->session()->get('unidadeId');
            //  dd($request->session()->get('unidadeId'));
            $request->session()->put('unidadeId', $ultimaLotacao);
        } else {
            $request->session()->put('unidadeId', $ultimaLotacao);
        }
        $lotacaoId = $request->session()->get('unidadeId');
        // dd($request->session()->has('unidadeId'));
        // dd($lotacao);


        /*
            CONSULTA TODAS PESSOAS QUE ESTÃO LOTADAS NO SETOR
          ** SETOR  INFORMADO
          ** MES/ANO INFORMADO
        */
        $pessoasLotadas = DB::connection('mysql_rh')->table('lotacoes')
        ->where(function ($query) {
            $query->whereMonth('a_partir_de_ini','<=', 5)
                  ->whereYear('a_partir_de_ini','<=',2021);
                })
            ->where(function ($query2) {
                $query2->where(function ($query2){
                    $query2->whereMonth('a_partir_de_fim','>=', 5)
                    ->whereYear('a_partir_de_fim','>=', 2021);
                });
                  $query2->orWhere(function ($query3){
                    $query3->whereNull('a_partir_de_fim');
                });
                  ;
                })
                ->where('unidade_id', $ultimaLotacao)
                ->leftJoin('pessoas', 'pessoas.id', '=', 'lotacoes.pessoa_id')
                ->orderBy('pessoas.nome')
                // ->toSql();
                ->get();


               //FOR-EACH PESSOASLOTADAS
                $listaPessoasDias = array();
                foreach ($pessoasLotadas as $pessoaLotada ) {
                    // dd($pessoaLotada );
                    $ini = $pessoaLotada->a_partir_de_ini;
                    $fim = $pessoaLotada->a_partir_de_fim;
                    $exercico = $pessoaLotada->exercicio_ini;

                    // $pessoaLotada->a_partir_de_ini = "2021-04-03";
                    // dd($mesFrequencia);
                    // dd(date('m', strtotime($pessoaLotada->a_partir_de_ini)));
                    // dd(((int)date('m', strtotime($pessoaLotada->a_partir_de_ini)) < (int)$mesFrequencia ));
                    if(
                        (int)date('Y', strtotime($pessoaLotada->a_partir_de_ini)) < $mesFrequencia
                        ||
                        ((int)date('Y', strtotime($pessoaLotada->a_partir_de_ini)) == $anoFrequencia && (int)date('m', strtotime($pessoaLotada->a_partir_de_ini)) < $mesFrequencia )
                      )
                    {
                        $ini = date($anoFrequencia . '-' . $mesFrequencia  . '-01');
                        // $anoFrequencia . '-' . $mesFrequencia . '-' . '01';
                    }
                    // dd($ini);
                    // dd((date('m',$pessoaLotada->a_partir_de_fim) < $mesFrequencia ));
                    if(
                        (int)date('Y', strtotime($pessoaLotada->a_partir_de_fim)) > $anoFrequencia
                        ||
                         ((int)date('Y', strtotime($pessoaLotada->a_partir_de_fim)) == $anoFrequencia
                         &&
                         (int)date('m', strtotime($pessoaLotada->a_partir_de_fim)) > $mesFrequencia )
                        ||
                        $pessoaLotada->a_partir_de_fim == null
                      )
                    {
                        // date('Y-m-t', strtotime($query_date))
                        $fim = date($anoFrequencia . '-' . $mesFrequencia . '-' . date('t', strtotime($ini))  );
                        // $anoFrequencia . '-' . $mesFrequencia . '-' . '01';
                    }



                    /*
                    ** TRANSFORMA O PERIODO DA LOTACAO EM ARRAY COM DIA-DIA DO PERIODO LOTADOS.
                    */
                    $first = $ini ;
                    $last = $fim ;
                    $step = '+1 day';
                    $output_format = 'Y-m-d' ;
                    $dates = array();
                    $current = strtotime($first);
                    $last = strtotime($last);
                    $dias = array();

                    while( $current <= $last ) {

                        // $dias[] = date('d', strtotime($current));
                        $dates[] = date($output_format, $current);
                        $current = strtotime($step, $current);
                    }

                    $listaPessoasDias[] = ["pessoa_id" => $pessoaLotada->pessoa_id, "dias" => $dates];
                }
                //FIM FOR-EACH PESSOASLOTADAS


                /*
                ** TRANSFORMAR O ARRAY DE PESSOAS/DIAS PERIODO EM APENAS UM RESULTADO POR PESSOA
                */
                $ultima = 0;
                $listaPessoasPeriodo[] = array();
                $diasUsuario =  array();
                $i = count($listaPessoasDias);
                foreach ($listaPessoasDias as $pessoaDias) {
                    # code...
                    $next = !!(--$i);

                    if($ultima == 0 )
                    {

                        $diasUsuario = $pessoaDias["dias"];
                        if ($next) {
                            // $listaPessoasPeriodo[]= ['pessoa_id' => $ultima, 'dias' => $diasUsuario];
                        }else
                        {
                            $listaPessoasPeriodo[] = ['pessoa_id' => $pessoaDias['pessoa_id'] ,'dias' => $pessoaDias["dias"]];
                        }
                    }
                    else if($ultima == $pessoaDias['pessoa_id'])
                    {
                        $diasUsuario = array_merge( $diasUsuario, $pessoaDias["dias"]);
                        // dd($diasUsuario );
                        if ($next) {

                        }else
                        {
                            $listaPessoasPeriodo[]= ['pessoa_id' => $ultima, 'dias' => $diasUsuario];
                        }
                        // dd($diasUsuario);
                    } else
                    {
                        if ($next) {
                            $listaPessoasPeriodo[]= ['pessoa_id' => $ultima, 'dias' => $diasUsuario];
                        }else
                        {
                            $listaPessoasPeriodo[]= ['pessoa_id' => $ultima, 'dias' => $diasUsuario];
                            $listaPessoasPeriodo[]= ['pessoa_id' => $pessoaDias['pessoa_id'] ,'dias' => $pessoaDias["dias"]];
                        }
                    }
                    $ultima = $pessoaDias['pessoa_id'];
                }
                //FIM TRANSFORMAR O ARRAY DE PESSOAS/DIAS PERIODO EM APENAS UM RESULTADO POR PESSOA

                // dd($listaPessoasDias);
                // dd($listaPessoasPeriodo );

                // dd(array_column($listaPessoasPeriodo,"pessoa_id"));

        $frequencias = DB::connection('mysql_freq')->table('frequencias')
        ->whereIn('frequencias.pessoa_id', array_column($listaPessoasPeriodo,"pessoa_id"))
        ->select('frequencias.*', 'rh.pessoas.nome','rh.matriculas.matricula')
        ->leftJoin('rh.pessoas','pessoas.id','frequencias.pessoa_id')
        ->leftJoin('rh.matriculas','matriculas.pessoa_id','pessoas.id')
        ->get()
        ;
        // dd(array_column($listaPessoasPeriodo,"pessoa_id"));
        // dd($frequencias);

                $x = '';
                // dd($frequencias);
                foreach ($listaPessoasPeriodo as $pessoaPeriodo) {
                    # code...
                    // dd($pessoaPeriodo['pessoa_id']);

                    if(!empty($pessoaPeriodo)){
                        foreach ($frequencias as $frequencia) {

                            if(!empty($frequencia) && in_array($pessoaPeriodo['pessoa_id'], $frequencia))
                            {
                                $x += 'Contem: ' . $pessoaPeriodo['pessoa_id'] . '|';
                            }
                        }
                    }
                }
                dd($x);




        return view('frequencias.index',compact('frequencias', 'listaPessoasPeriodo',  'mesAnoFrequencia') );
    }


    public function addPessoa($pessoa)
    {
        $novaPessoa = new Pessoa();

        $novaPessoa->nome = $pessoa->nome;
        $novaPessoa->pai = $pessoa->pai;
        $novaPessoa->mae = $pessoa->mae;
        $novaPessoa->cpf = $pessoa->cpf;
        $novaPessoa->data_nascimento = $pessoa->nascimento;

        Pessoa::insert($novaPessoa);

        // dd($novaPessoa);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pessoas');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
        $novaPessoa = new Pessoa();

        $novaPessoa->nome = $request->pessoa->nome;
        $novaPessoa->pai = $request->pessoa->pai;
        $novaPessoa->mae = $request->pessoa->mae;
        $novaPessoa->cpf = $request->pessoa->cpf;
        $novaPessoa->data_nascimento = $request->pessoa->nascimento;


        Pessoa::create([$novaPessoa]);
        // Estados::create($request->all());

        return redirect()->route('pessoas.show')
            ->with('success', 'Estado criado com sucesso.');
    }

    public function store2(Request $request, Pessoa $pessoa)
    {
        dd($request);
        $novaPessoa = new Pessoa();

        $novaPessoa->nome = $request->pessoa->nome;
        $novaPessoa->pai = $request->pessoa->pai;
        $novaPessoa->mae = $request->pessoa->mae;
        $novaPessoa->cpf = $request->pessoa->cpf;
        $novaPessoa->data_nascimento = $request->pessoa->nascimento;


        Pessoa::create([$novaPessoa]);
        // Estados::create($request->all());

        return redirect()->route('pessoas.show')
            ->with('success', 'Estado criado com sucesso.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function show2(Request $request, Pessoa $pessoa)
    {
        // Verifica se existe a sessão
        if ($request->session()->has('cart')) {
            dd($request->session()->get('cart'));
        }

        // Verificar se existe o item na sessão
        // if ($request->session()->exists('products')) {
        //     dd($request->session()->get('cart'));
        // }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
