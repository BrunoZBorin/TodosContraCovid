<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PacienteFormRequest extends FormRequest
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
            'cns'=>'required',
            'data_nasc'=>'required',
            'primeira_avaliacao_medica'=>'required', 
            'isolamento_ate'=>'required',
            'data_inicio_sintomas'=>'required',
            'data_coleta_exames'=>'required',
            'unidade_sintomatica_id'=>'required',
            'convenio'=>['required',Rule::in(['SUS', 'particular'])],
            'unidade_saude_id'=>'required',
            'tipo_exame'=>['required',Rule::in(['PCR-RT', 'sorologia', 'teste_rapido'])],
            'data_resultado'=> 'required',
            'resultado_exame'=>['required',Rule::in(['positivo', 'negativo', 'aguardando_resultado'])],
            'grupo_risco'=>['required',Rule::in(['sim', 'nao'])],
        ];
    }

    public function messages()
    {
        return[
            'nome.required' => 'É necessário preencher o campo de nome',
            'cep.required' => 'É necessário preencher o campo de cep',
            'telefone.required' => 'É necessário preencher o campo de telefone',
            'numero.required' => 'É necessário preencher o campo de numero',
            'cns.required' => 'É necessário preencher o campo de cns',
            'data_nasc.required' => 'É necessário preencher o campo de data nascimento',
            'primeira_avaliacao_medica.required' => 'É necessário preencher o campo de primeira avaliacao medica',
            'isolamento.required' => 'É necessário preencher o campo de isolamento',
            'data_inicio_sintomas.required' => 'É necessário preencher o campo de data inicio sintomas',
            'data_coleta_sintomas.required' => 'É necessário preencher o campo de data coleta sintomas',
            'unidade_saude_id.required' => 'É necessário preencher o campo de unidade saude',
            'convenio.required' => 'É necessario preencher o campode convenio',
            'convenio.in'=>'Preencha se é SUS ou particular',
            'tipo_exame.required' => 'É necessário preencher o campo de tipo exame',
            'tipo_exame.in'=>'Preencha se é PCR-RT, sorologia ou teste_rapido',
            'data_resultado.required' => 'É necessário preencher o campo de data resultado',
            'resultado_exame.required' => 'É necessário preencher o campo de resultado exame',
            'resultado_exame.in'=> 'Preencha com positivo,negativo ou aguardando_resultado',
            'grupo_risco.required' => 'É necessário preencher o campo de grupo risco',
            'grupo_risco.in'=>'Preencher se sim ou nao',
            'telefone.min' => 'O campo telefone precisa de ao menos 8 caracteres',
            'cep.min' => 'O campo cep precisa de ao menos 8 caracteres',
        ];
    }
}
