<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AtendimentoFormRequest extends FormRequest
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
            'isolamento'=> ['required',Rule::in(['sim', 'nao'])],
            'orientacao'=> ['required',Rule::in(['bem', 'confuso', 'sonolento'])],
            'apetite'=> ['required',Rule::in(['bom', 'diminuido', 'anorexico'])],
            'febre'=> ['required',Rule::in(['ausente', 'pico_baixo', 'persistente'])],
            'tosse'=> ['required',Rule::in(['ausente', 'fala_sem_tossir', 'fala_tossindo'])],
            'falta_de_ar'=> ['required',Rule::in(['ausente', 'presente_ao_esforco', 'intensa_no_repouso'])],
            'paciente_id'=> 'required', 
            'usuario_id'=> 'required', 
            'data_hora_ligacao'=> 'required'
        ];
    }

    public function messages()
    {
        return[
            'required' => 'É necessário preencher todos os campos',
            'isolamento.in'=> 'Preencher com sim ou nao',
            'orientacao.in'=> 'Preencher com bem ou confuso ou sonolento',
            'apetite.in'=> 'Preencher com bom ou diminuido ou anorexico',
            'febre.in'=> 'Preencher com ausente ou pico_baixo ou persistente',
            'tosse.in'=> 'Preencher com ausente ou fala_sem_tossir ou fala_tossindo',
            'falta_de_ar.in'=> 'Preencher com ausente ou presente_ao_esforco ou intensa_no_repouso',
        ];
    }
}
