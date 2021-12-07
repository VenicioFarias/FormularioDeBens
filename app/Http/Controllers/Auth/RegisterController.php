<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Matricula;
use Illuminate\Http\Request;
use App\Helpers\DataHoraHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            // 'cpf'  => $data['cpf'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function userPessoa(Request $request)
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

                                // dd($matricula->pessoa);

        if ($matricula)
            return response()->json($matricula->pessoa);

        return response()->json(['error' => true, 'message' => 'Pessoa não encontrada!']);
    }

    // public function update(UpdateAccount $request)
    // {
    //     $usuario = Auth::user(); // resgata o usuario

    //     $usuario->username = Request::input('username'); // pega o valor do input username
    //     $usuario->email = Request::input('email'); // pega o valor do input email

    //     if ( ! Request::input('password') == '') // verifica se a senha foi alterada
    //     {
    //         $user->password = bcrypt(Request::input('password')); // muda a senha do seu usuario já criptografada pela função bcrypt
    //     }

    //     $user->save(); // salva o usuario alterado =)

    //     Flash::message('Atualizado com sucesso!');
    //     return Redirect::to(...); // redireciona pra rota que você achar melhor =)
    // }

}
