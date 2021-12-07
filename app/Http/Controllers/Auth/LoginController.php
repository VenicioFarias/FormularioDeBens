<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public function username()
    {
        return 'name';
    }


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public  function index()
    {
        return view('Auth.login2');
    }

    // public function minhaUnidade()
    // {
    //     $pessoa = \App\Models\Pessoa::with('lotacaoAtual')->where('user_id', auth()->user()->id)->first();
    //     //dd($pessoa->lotacaoAtual->unidade_id);
    //     return $pessoa->lotacaoAtual->unidade_id ? \App\Models\Unidade::find($pessoa->lotacaoAtual->unidade_id) : null;
    // }

    // public function departamentoPai($minhaUnidade = null)
    // {
    //     if (!$minhaUnidade)
    //         $minhaUnidade = $this->minhaUnidade()->id;

    //     $unidade = Unidade::find($minhaUnidade);
    //     if ($unidade->unidade_id == 1) // 1 - Polícia Civil do Ceará (Raiz)
    //     {
    //         Session::put('departamento_unidade', $unidade);

    //         return $unidade;
    //     }

    //     return $this->departamentoPai($unidade->unidade_id);
    // }


}
