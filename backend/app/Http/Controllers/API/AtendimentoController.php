<?php

namespace App\Http\Controllers\API;

use App\Atendimento;
use App\AtendimentoSinais;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AtendimentoFormRequest;
use App\Paciente;
use Carbon\Carbon;
use App\Exports\AtendimentosExport;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;

class AtendimentoController extends Controller
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
        $atendimento = Atendimento::all();
        return response()->json($atendimento, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AtendimentoFormRequest $request)
    {
        $atendimento = Atendimento::create($request->all());
        $pontos = 0;
        switch ($request->febre) {
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
        switch ($request->tosse) {
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
        switch ($request->falta_de_ar) {
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
        $paciente = Paciente::findOrFail($request->paciente_id);
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
        
        return response()->json($atendimento, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $atendimento = Atendimento::findOrFail($id);
        return response()->json($atendimento, 200);
    }

    public function show_atendimento_sinais($id)
    {
        $atendimento = Atendimento::findOrFail($id);
        $sinais = DB::table('sinais')
        ->join('atendimento_sinais','sinais.id','=','atendimento_sinais.sinais_id')
        ->where('atendimento_sinais.atendimento_id',$id)
        ->get();
        return response()->json([$atendimento, $sinais], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AtendimentoFormRequest $request, $id)
    {
        $atendimento = Atendimento::findOrFail($id);
        $atendimento->fill($request->all());
        $atendimento->save();
        return response()->json($atendimento, 200);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $atendimento = Atendimento::findOrFail($id);
        $atendimento->delete();
        return response()->json([], 200);
    }

    public function export_excel() 
    {
        return $this->excel->download(new AtendimentosExport, 'atendimentos.xlsx');
    }
    public function export_pdf() 
    {
        return $this->excel->download(new AtendimentosExport, 'atendimentos.pdf', Excel::DOMPDF);
    }
}
