<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidaModelo extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       return
                [

                'descricao_mod' => ['required','min:8'],
                'id_mar'  => 'required',

                ];

        }
    public function attributes()
    {
        return
                [
                'descricao_mod'=>'Descrição',
                'id_mar'=>'Marca',
                ];
    }

    public function messages()
    {
        return [
                     //'descricao_mod.required'=>'O Campo Descrição é teste.',
                    // 'descricao_mod.min'=>'O Campo Descrição tem que ter no minimo 8 caracteres.',
                    // 'id_mar.required'=>'O Campo Marca é Obrigatorio.',
        ];
    }
}
