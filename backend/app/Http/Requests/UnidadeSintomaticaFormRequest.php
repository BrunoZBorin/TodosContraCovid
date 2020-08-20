<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnidadeSintomaticaFormRequest extends FormRequest
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
            'cep' => 'required|min:8',
            'telefone'=> 'required|min:8',
            'numero'=> 'required',
        ];
    }

    public function messages()
    {
        return[
            'required' => 'É necessário preencher todos os campos',
            'telefone.min' => 'O campo telefone precisa de ao menos 8 caracteres',
            'cep.min' => 'O campo cep precisa de ao menos 8 caracteres',
        ];
    }
}
