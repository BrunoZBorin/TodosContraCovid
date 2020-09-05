<?php

namespace App\Exports;

use App\Paciente;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class PacientesExportPDF implements FromCollection,
ShouldAutoSize, 
WithMapping, 
WithHeadings,
WithEvents
{
    protected $id;

    function __construct($id) {
            $this->id = $id;
    }

    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = DB::table('users')
                    ->where('users.id',$this->id)
                    ->select('users.*')
                    ->first();
        $usuario =$user->unidade_saude_id;
        if($user->perfil=='monitoramento')
        {
            $dados = Paciente::where('unidade_saude_id', $usuario)->get();

            foreach($dados as $dado)
            {
                $dado->data_nasc = implode('/', array_reverse(explode('-', explode(' ', $dado->data_nasc)[0])));

                if($dado->resultado_exame == 'positivo')
                {
                    $dado->resultado_exame = 'Positivo';
                }
                else if($dado->resultado_exame == 'negativo')
                {
                    $dado->resultado_exame = 'Negativo';
                }
                else
                {
                    $dado->resultado_exame = 'Aguardando Resultado';
                }

                if($dado->convenio == 'particular')
                    $dado->convenio = 'Particular';
            }

            return $dados;
        }
        if($user->perfil=='municipal')
        {
            $dados = Paciente::all(   );

            foreach($dados as $dado)
            {
                $dado->data_nasc = implode('/', array_reverse(explode('-', explode(' ', $dado->data_nasc)[0])));

                if($dado->resultado_exame == 'positivo')
                {
                    $dado->resultado_exame = 'Positivo';
                }
                else if($dado->resultado_exame == 'negativo')
                {
                    $dado->resultado_exame = 'Negativo';
                }
                else
                {
                    $dado->resultado_exame = 'Aguardando Resultado';
                }
            }
            
            return $dados;
        }
        
    }
    public function map($paciente):array{
        return[
            $paciente->nome,
            $paciente->data_nasc,
            $paciente->cns,
            $paciente->resultado_exame,
            $paciente->telefone,
        ];
    }
    public function headings():array{
        return[
            'Nome',
            'Data Nascimento',
            'CNS',
            'Resultado Exame',
            'Telefone',
        ];
    }
    public function registerEvents():array{
        return[
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getStyle('A4:F1')->applyFromArray([
                    'font'=>[
                        'bold'=>true
                    ]
                ]); 
                
            }
        ];
    }
}
