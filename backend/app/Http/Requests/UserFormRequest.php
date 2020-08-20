<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
        return [
            'nome' => 'required',
            'password'=> 'required|min:8',
            'telefone'=> 'required|min:8',
            'email' => 'email:rfc,dns|unique:users',
            'perfil' => 'required',
            'unidade_saude_id' => 'required'
        ];
    }

    public function messages()
    {
        return[
            'required' => 'É necessário preencher todos os campos',
            'min' => 'O campo precisa de ao menos 8 caracteres',
            'email' => 'Preciso de um email válido',
            'unique' => 'Esse email já foi utilizado'
        ];
    }
}
