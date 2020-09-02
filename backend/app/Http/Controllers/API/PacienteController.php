<?php

namespace App\Http\Controllers\API;

use App\Atendimento;
use App\AtendimentoSinais;
use App\Familiar;
use App\Http\Controllers\Controller;
use App\Paciente;
use App\Sinais;
use Illuminate\Support\Facades\DB;
use App\PacienteComorbidades;
use Illuminate\Http\Request;
use App\Http\Requests\PacienteFormRequest;
use Carbon\Carbon;
use App\Exports\PacientesExport;
use Maatwebsite\Excel\Excel;


class PacienteController extends Controller
{
    private $excel;

    public function __construct(Excel $excel){
        $this->excel = $excel;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paciente = Paciente::all();
        return response()->json($paciente, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PacienteFormRequest $request)
    {
        $endereco = \Correios::cep($request->cep);
        $paciente = new Paciente();
        $paciente->fill($request->all());
        if(!empty($endereco)) {
            $paciente->logradouro = $endereco['logradouro'];
            $paciente->bairro = $endereco['bairro'];
            $paciente->cidade = $endereco['cidade'];
            $paciente->estado = $endereco['uf'];
        
            $paciente->save();
            return response()->json($paciente, 201);

        }else{
            $message = "O cep não existe";
            return response()->json($message, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);
        return response()->json($paciente, 200);
    }

    public function show_sinais_familiares($id)
    {
        $paciente = Paciente::findOrFail($id);
        $familiares = Familiar::all();
        $familiars = [];
        
        $atendimentos = DB::table('atendimentos')
            ->where('paciente_id',$id)
            ->get();
        
        $sinais = [];
        foreach($atendimentos as $atendimento){
            $sinais[] = DB::table('sinais')
            ->join('atendimento_sinais','sinais.id','=','atendimento_sinais.sinais_id')
            ->where('atendimento_sinais.atendimento_id',$atendimento->id)
            ->get();
        }

        $comorbidades = DB::table('comorbidades')
        ->join('paciente_comorbidades','comorbidades.id','=','paciente_comorbidades.comorbidades_id')
        ->where('paciente_comorbidades.paciente_id',$id)
        ->get();
        
        foreach($familiares as $familiar){
            if($familiar->paciente_id == $id)
            {
                $familiars[] = $familiar;
            }
        }
        return response()->json([$paciente, $familiars, $atendimentos, $sinais, $comorbidades], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PacienteFormRequest $request, $id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->fill($request->all());
        $paciente->save();
        return response()->json($paciente, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->delete();
        return response()->json([], 200);
    }

    public function primeiro_cadastro(Request $request)
    {
        $endereco = \Correios::cep($request->paciente_cep);
        $paciente = new Paciente();
        if(empty($endereco)) {
            return response("Endereço não encontrado.", 500);
        }

        $paciente = Paciente::create([
            'logradouro' => $endereco['logradouro'],
            'bairro' => $endereco['bairro'],
            'cidade' => $endereco['cidade'],
            'estado' => $endereco['uf'],
            'nome' => $request->paciente_nome,
            'cep' => $request->paciente_cep,
            'numero' => $request->paciente_numero,
            'telefone' => $request->paciente_telefone,
            'cns' => $request->paciente_cns,
            'data_nasc' => $request->paciente_data_nasc,
            'obito' => $request->paciente_obito,
            'primeira_avaliacao_medica' => $request->paciente_primeira_avaliacao_medica,
            'isolamento_ate' => $request->paciente_isolamento_ate,
            'data_inicio_sintomas' => $request->paciente_data_inicio_sintomas,
            'data_coleta_exames' => $request->paciente_data_coleta_exames,
            'unidade_sintomatica_id' => $request->paciente_unidade_sintomatica_id,
            'convenio' => $request->paciente_convenio,
            'unidade_saude_id' => $request->paciente_unidade_saude_id,
            'tipo_exame' => $request->paciente_tipo_exame,
            'data_resultado' => $request->paciente_data_resultado,
            'resultado_exame' => $request->paciente_resultado_exame,
            'grupo_risco' => $request->paciente_grupo_risco
        ]);
        
        $atendimento = new Atendimento();
        $atendimento->data_hora_ligacao = $request->atendimento_data_hora_ligacao;
        $atendimento->isolamento = $request->atendimento_isolamento;
        $atendimento->orientacao = $request->atendimento_orientacao;
        $atendimento->apetite = $request->atendimento_apetite;
        $atendimento->febre = $request->atendimento_febre;
        $atendimento->tosse = $request->atendimento_tosse;
        $atendimento->falta_de_ar = $request->atendimento_falta_de_ar;
        $atendimento->observacoes_gerais = $request->atendimento_observacoes_gerais;
        $atendimento->paciente_id = $paciente->id;
        $atendimento->usuario_id = $request->atendimento_usuario_id;
        $pontos = 0;
        switch ($request->atendimento_febre) {
            case 'ausente':
                $pontos+=1;
                break;
            case 'pico_baixo' :
                $pontos+=2;
                break;
            case 'persistente':
                $pontos+=3;
                break;
        }
        switch ($request->atendimento_tosse) {
            case 'ausente':
                $pontos+=1;
                break;
            case 'fala_sem_tossir':
                $pontos+=2;
                break;
            case'fala_tossindo':
                $pontos+=3;
                break;
        }
        switch ($request->atendimento_falta_de_ar) {
            case 'ausente':
                $pontos+=1;
                break;
            case 'presente_ao_esforco':
                $pontos+=2;
                break;  
            case 'intensa_no_repouso':
                $pontos+=3;
                break;
        }
        
        $idade = Carbon::parse($paciente['data_nasc'])->age;
        if($idade<30){
            $pontos+=1;
        }
        if($idade>=30 && $idade<60){
            $pontos+=2;
        }
        if($pontos>=60){
            $pontos+=3;
        }
               
        if($pontos<=6){
            $atendimento->orientacao_conduta = 'manter_isolamento_domiciliar'; 
        }
        if($pontos>=7 && $pontos<=9){
            $atendimento->orientacao_conduta = 'encaminhar_unidade_sintomatica'; 
        }
        if($pontos>=10){
            $atendimento->orientacao_conduta = 'encaminhar_SAMU';
        }
        
        $atendimento->save();
        
        $familiares = $request->familiares;
        $familiars = [];
        foreach($familiares as $familia)
        {
            $familiars[]=Familiar::create([
                'nome' => $familia['nome'],
                'sintomatico' => $familia['sintomatico'],
                'exame' => $familia['exame'],
                'paciente_id' => $paciente->id,
                ]);
        }

        $comorbidades = $request->comorbidades;
        $paciente_comorbidades = [];
        foreach($comorbidades as $comorbidade)
        {
            $paciente_comorbidades[] = PacienteComorbidades::create([
                'paciente_id' => $paciente->id,
                'comorbidades_id' => $comorbidade
            ]);
        }

        $sinais = $request->sinais;
        $atendimento_sinais = [];
        foreach($sinais as $sinal)
        {
            $atendimento_sinais[] = AtendimentoSinais::create([
                'atendimento_id' => $atendimento->id,
                'sinais_id' => $sinal
            ]);
        }

        return response()->json([$paciente,$atendimento, $familiars, $paciente_comorbidades, $atendimento_sinais], 201);
    }

    public function cep(Request $request)
    {
        $endereco = \Correios::cep($request->cep);
        return response()->json($endereco, 200);
    }

    public function export_excel() 
    {
        return $this->excel->download(new PacientesExport, 'Pacientes.xlsx');
    }

    public function export_pdf() 
    {
        return $this->excel->download(new PacientesExport, 'Pacientes.pdf', Excel::DOMPDF);
    }
    public function obitos(){
        $obitos = DB::table('pacientes')
                    ->whereNotNull('obito')
                    ->count();
        return response()->json($obitos, 200);
    }

    public function idades(){
        $pacientes = Paciente::all();
        $menosTrinta = 0;
        $menosSessenta = 0;
        $maisSessenta = 0;
        foreach($pacientes as $paciente){
            $idade = Carbon::parse($paciente['data_nasc'])->age;
            if($idade<30){
                $menosTrinta++;
            }
            if($idade>=30 && $idade<60){
                $menosSessenta++;
            }
            if($idade>=60){
                $maisSessenta++;
            }   
        }
        return response()->json([$menosTrinta, $menosSessenta, $maisSessenta], 200);
    }
    
    public function pacientes_com_comorbidades(){
        $comorbidades = DB::table('pacientes')
                    ->join('paciente_comorbidades', 'pacientes.id', '=', 'paciente_comorbidades.paciente_id')
                    ->whereNotNull('paciente_comorbidades.comorbidades_id')
                    ->count();
        
        return response()->json($comorbidades, 200);
    }

}
