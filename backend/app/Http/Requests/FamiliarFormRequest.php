<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FamiliarFormRequest extends FormRequest
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
            'sintomatico'=> 'required', 
            'exame'=> ['required',Rule::in(['positivo', 'negativo', 'aguardando_resultado'])],
            'paciente_id'=> 'required'
        ];
    }

    public function messages()
    {
        return[
            'required' => 'É necessário preencher todos os campos',
            'exame.in' => 'O campo deve ser preenchido como positivo, negativo ou aguardando resultado'
        ];
    }
}
