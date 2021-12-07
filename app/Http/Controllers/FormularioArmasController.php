<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Armas;
use App\Models\Algema;
use App\Models\Cargos;
use App\Models\Colete;
use App\Models\Unidade;
use Illuminate\Http\Request;
use App\Models\FormularioBensDto;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class FormularioArmasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            // $verifica['success']=false;
            // $verifica['menssage']='mensagem json! falsa';
            // echo json_encode($verifica);
            // $verifica['success']=true;
            // $verifica['menssage']='mensagem json! Verdadeira';
            // echo json_encode($verifica);
            $cargos = Cargos::orderBy('cargo')->get();
            // dd($cargos);
            $unidades = Unidade::get();
            return view('FormularioArmas.formularioArmas',compact('unidades','cargos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $cargos = Cargos::orderBy('cargo')->get();
        // dd($cargos);
        $unidades = Unidade::get();
        return view('FormularioArmas.formulario',compact('unidades','cargos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salvar(Request $request)
    {
        $chars = array("/","-",".","(",")","@"," ",);
        // /[^xX0-9]/g,''
        // .replace(/[^xX0-9]/g,'')
        // $teste = array(/[^xX0-9]/g,''");
        $matricula = $request->matricula;
        $matricula = str_replace($chars,'', $request->matricula);
        $cpf = str_replace($chars,'', $request->cpf);
        $formulario = new FormularioBensDto();
        $formulario->nome = $request->nome;
        $formulario->matricula = $matricula;
        $formulario->telefone = $request->telefone;
        $formulario->cpf = $cpf;
        $formulario->lotacao = $request->lotacao;
        $formulario->email = $request->email;
        $formulario->cargo = $request->cargo;
        $formulario->data_cadastro = Carbon::now();
        // dd($formulario);
        $formulario->save();
        // dd($formulario->id);
        $id_imprimir = $formulario->id;
        //dd($id_imprimir);

        $listaArmas = json_decode($request->armas[0]);
        // dump($listaArmas);
        if ($listaArmas != null)
        {

            foreach($listaArmas as $Arma)
            {
                foreach($Arma as $itemArma)
                {

                    $arma_obj = new Armas();
                    $arma_obj->modelo = $itemArma->modelo;
                    $arma_obj->num_serie = $itemArma->serie;
                    $arma_obj->calibre = $itemArma->calibre;
                    $arma_obj->carregadores = $itemArma->carregadores;

                    $arma_obj->form_id = $formulario->id;
                    $arma_obj->save();

                }

            }
        }
        $listaColetes = json_decode($request->coletes[0]);
        // dump($listaColetes);
        if ($listaColetes != null) {
            # code...
            foreach($listaColetes as $coletes)
                {
                    foreach($coletes as $item)
                    {
                        $colete = new Colete();
                        $colete->marca = $item->marca;
                        $colete->num_serie = $item->serie;
                        $colete->tamanho = $item->tamanho;
                        $colete->sexo = $item->sexo;
                        $colete->form_id = $formulario->id;
                        $colete->save();
                    }

                }
         }


            $listaAlgemas = json_decode($request->algemas[0]);
            // dd($listaAlgemas);
            if ($listaAlgemas != null) {

                foreach($listaAlgemas as $algemas)
                {
                    foreach($algemas as $itemAlgema)
                    {
                        $algema_obj = new Algema();
                        $algema_obj->marca = $itemAlgema->marca;
                        $algema_obj->tipo = $itemAlgema->tipo;
                        $algema_obj->num_serie = $itemAlgema->serie;
                        $algema_obj->form_id = $formulario->id;
                        $algema_obj->save();
                    }

                }
            }

            // dd($formulario,$listaArmas,$listaColetes,$listaAlgemas);
            // dd($listaColetes);
            //return response()->json($result);
            // $verifica['success']=true;
            // $verifica['menssage']='mensagem json! Verdadeira';
            // echo json_encode($verifica);
        // $request->all();
        //Key
        // session_destroy();
        return redirect()->action(
            [FormularioArmasController::class, 'imprimir'], ['id_imprimir' => Crypt::encryptString($id_imprimir) ]
        );

    }
    public function imprimir(Request $request)
    {

        try {
            $decrypted = Crypt::decryptString($request->id_imprimir);
            // dd($request->id_imprimir);
            $formulario = FormularioBensDto::where('id', $decrypted)->first();
            // dd($formulario);
            $armas   = Armas::where('form_id',  $decrypted)->get();
            $coletes = Colete::where('form_id', $decrypted)->get();
            $algemas = Algema::where('form_id', $decrypted)->get();

            return view('FormularioArmas.imprimir',compact('armas','coletes','algemas','formulario'));
        } catch (DecryptException $e) {

            return redirect()->action(
                [FormularioArmasController::class, 'index']
            );
        }




        // dd($coletes);


    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // $request->all();
        //dd($request->all());
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
