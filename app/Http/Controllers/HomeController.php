<?php

namespace App\Http\Controllers;

use DateTime;

use App\Models\User;
use App\Models\Cidades;
use App\Models\Unidade;
use App\Models\Matricula;
use Illuminate\Http\Request;
use App\Helpers\DataHoraHelper;
use App\Models\Usuarios_perfis;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscaPessoa(Request $request)
    {
        //$pessoa = Pessoa::where('matricula', $request->input('matricula'))->first(['id', 'nome']);
        // if ( $request->filled('data_hora_ini') )
        //     $data = DataHoraHelper::converteData( $request->input('data_hora_ini'), 'dd/mm/yyyy h - yyyy-mm-dd' );
        // else
            $data = date('Y-m-d');

        $matricula = Matricula::with('pessoa')
                                ->where('matricula', 'like', $request->input('matricula'))
                                ->where('posse', '<=', $data)
                                ->orderBy('posse', 'desc')
                                ->first();

        if ($matricula)
            return response()->json($matricula->pessoa);

        return response()->json(['error' => true, 'message' => 'Pessoa não encontrada!']);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(User $users)
    {


        // dd($municipiosGeo);
        //print_r($meuArray);
        // $per = User::recuperaPerfil();
        // $perfil = Session::get();
        //  $id = 1;
        //  $users = User::where('id',$id)->first();
        //  $perfis = $users->meuPerfil()->first();
        //  dump($users);
        //  dd($perfis->perfil_id);
        // //$this->authorize('lista-usuario',)
        // if (Gate::denies('administrador', $per)) {


        // } else {

        //     abort(403,'não Autorizado');

        // }


        // $dateStart = new DateTime('2017-02-08');
        // $dateNow   = new DateTime('2017-02-18');
        // // $dateNow   = new DateTime(date('Y-m-d'));

        // $dateDiff = $dateStart->diff($dateNow);
        // //dd($dateDiff->days);

        // $unidades = Unidade::get();

        // return view('home',compact('unidades'));
            return view('home');

    }
     /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('modal.modal-adicionar-extra-pessoa', [
                    'inicio' => $this->inicio,
                    'fim' => $this->fim,
                    'hoje' => $this->hoje,
                    'mes' => $this->mes,
                    'ano' => $this->ano,
                    'unidades' => $this->unidades,
                    'operacoes' => $this->operacoes,
                ]);
    }

    public function LerArquivoCSV()
    {
      // PEGA UM ARQUIVO EM CSV LE E SALVA NA TABELA
        $municipiosGeo = Array();
        $file = fopen('../tests/limite_municipalAS_WKT.csv', 'r');$row = 0;
        while (($line = fgetcsv($file)) !== false)
        {

            if ($row++ == 0)
            {
                continue;
            }
            $municipiosGeo [] = [
                'poligono' => $line[0],
                'id' => $line[1],
                'geocodigo' => $line[2],
                'municipio' => $line[3],
                'ano_de_criacao' => $line[4],
                'regiao_de_planejamento' => $line[5],
                'area_km2' => $line[6]
            ];
            //  $municipios = new Cidades;
            //     $municipios = Cidades::where('cidade', 'LIKE', "%{$municipiosGeo['municipio']}%")->get();
            // dump($municipiosGeo);
                // ->get();
                // $cidade = Cidades::where('cidade', 'LIKE', "%{$municipiosGeo['municipio']}%")->where('estado_id',6) ->get();
                // dd($cidade);

        }
        // dd($municipiosGeo);
        fclose($file);

        foreach($municipiosGeo as $k => $cid){
             dump($cid['municipio']);
            dd($municipiosGeo);
            $cidades = Cidades::where('cidade', 'LIKE', "{$cid['municipio']}")->where('estado_id',6)->get()->first();

            if($cidades == null || empty($cidades))
            {
                dump('Não encontrei: ' . $cid['municipio']);
            }
            else

            {
                //dump($cidades->id,$cid['poligono']);
                // dd($cidades->id);
            $atualizarGeo = DB::connection('Geo')->table('cidades')
            ->where('id', $cidades->id)
            ->update(['geo' =>DB::raw("(ST_GeomFromText('{$cid['poligono']}'))"),'regiao_planejamento' => $cid['regiao_de_planejamento']]);
                //$cidades->geo = $cid['poligono'];
                //dump($cidades);
                dump('Atualização: ' . $atualizarGeo . ' Cidade: ' . $cid['municipio'] , $cidades );

            }
            dd($municipiosGeo);
        }
    }




}


// PEGA UM ARQUIVO EM CSV LE E SALVA NA TABELA
// $meuArray = Array();
// $file = fopen('../tests/limite_municipalAS_WKT.csv', 'r');$row = 0;
// while (($line = fgetcsv($file)) !== false)
// {

//     if ($row++ == 0)
//     {
//         continue;
//     }
//     $municipiosGeo[] = [
//         'poligono' => $line[0],
//         'id' => $line[1],
//         'geocodigo' => $line[2],
//         'municipio' => $line[3],
//         'ano_de_criacao' => $line[4],
//         'regiao_de_planejamento' => $line[5],
//         'area_km2' => $line[6]
//     ];
//     // $municipios = new Cidades;
//     // $municipios = Cidades::where('cidade', 'LIKE', "%{$municipiosGeo['municipio']}%")->get();
//     // dd($municipiosGeo);
//     // ->get();
//     //  $cidade = Cidades::where('cidade', 'LIKE', "%{$municipiosGeo['municipio']}%")->where('estado_id',6) ->get();
//     //  dd($cidade);

// }
// fclose($file);

// foreach($municipiosGeo as $k => $cid){
//     $cidades = Cidades::where('cidade', 'LIKE', "{$cid['municipio']}")->where('estado_id',6)->get()->first();
//     if($cidades == null || empty($cidades))
//     {
//         dump('Não encontrei: ' . $cid['municipio']);
//     }
//     else

//     {
//         //dump($cidades->id,$cid['poligono']);
//         // dd($cidades->id);
//         $atualizarGeo = DB::connection('Geo')->table('cidades')
//       ->where('id', $cidades->id)
//       ->update(['geo' =>DB::raw("(ST_GeomFromText('{$cid['poligono']}'))")]);
//          //$cidades->geo = $cid['poligono'];
//          //dump($cidades);
//          dump('Atualização: ' . $atualizarGeo . ' Cidade: ' . $cid['municipio'] , $cidades );

//     }

//  }
